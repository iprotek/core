<template>
    <div class="tree">
        <div class="mb-2">
            <input 
                type="text" 
                v-model="searchQuery" 
                class="form-control form-control-sm" 
                placeholder="Search policies..."
            />
        </div>
        <TreeNode
            v-for="(value, key) in tree"
            :key="key"
            :name="key"
            :node="value"
            :depth="0"
            :path="key"
            :is_plus="is_plus"
            
        />
    </div>
</template>

<script setup>
import { computed, defineComponent, h, ref, provide, inject, watch } from 'vue';

const props = defineProps({
    is_plus: { type: Boolean, default: true },
    routes: {type:Array, default: []},
    policyControlList:{type:Array, default: []}
});

const emit = defineEmits(['move-route', 'move-routes']);

const searchQuery = ref('');
provide('searchQuery', searchQuery);

provide('onMoveRoute', (route) => {
    emit('move-route', route);
});

provide('onMoveRoutes', (routesList) => {
    emit('move-routes', routesList);
});

const descriptionMap = computed(() => {
    const map = {};
    if (props.policyControlList) {
        props.policyControlList.forEach(item => {
            if (item && item.name) {
                map[item.name] = item.description || '';
            }
        });
    }
    return map;
});

provide('getRouteDescription', (route) => {
    return descriptionMap.value[route] || '';
});

function isRouteMatch(route, query) {
    if (!query) return true;
    const lowerQuery = query.toLowerCase();

    // Check route name
    if (route.toLowerCase().includes(lowerQuery)) return true;

    // Check description
    const desc = descriptionMap.value[route];
    if (desc && desc.toLowerCase().includes(lowerQuery)) return true;

    // Check formatted segments (e.g. "Data Model" matching "data-model")
    const parts = route.split('.');
    if (parts[0] === 'api') parts.shift();
    
    const formatName = (text) => {
        return text
            .replace(/-/g, ' ')
            .replace(/\b\w/g, l => l.toUpperCase());
    };

    for (let part of parts) {
        const formatted = formatName(part).toLowerCase();
        if (formatted.includes(lowerQuery)) {
            return true;
        }
    }

    return false;
}

function buildTree(data) {
    const tree = {};

    data.forEach(route => {
        const parts = route.split('.');

        if (parts[0] === 'api') parts.shift();

        let current = tree;

        parts.forEach((part, index) => {
            if (index === parts.length - 1) {
                if (!current.__files) current.__files = [];
                current.__files.push(route);
            } else {
                if (!current[part]) current[part] = {};
                current = current[part];
            }
        });
    });

    return tree;
}

const filteredRoutes = computed(() => {
    return props.routes.filter(route => isRouteMatch(route, searchQuery.value));
});

const tree = computed(() => buildTree(filteredRoutes.value));

/* collect all nested routes */
function collectRoutes(node) {
    let result = [];

    if (node.__files) result.push(...node.__files);

    for (const key in node) {
        if (key !== '__files') {
            result.push(...collectRoutes(node[key]));
        }
    }

    return result;
}

const TreeNode = defineComponent({
    name: 'TreeNode',
    props: {
        data:[],
        name: String,
        node: Object,
        depth: Number,
        path: String,
        is_plus: Boolean
    },

    setup(props) {
        //var vm = this;
        const expanded = ref(false);
        const onMoveRoute = inject('onMoveRoute');
        const onMoveRoutes = inject('onMoveRoutes');
        const getRouteDescription = inject('getRouteDescription');
        const searchQuery = inject('searchQuery');

        if (searchQuery) {
            watch(searchQuery, (newVal) => {
                if (newVal) {
                    expanded.value = true;
                }
            });
        }

        const toggle = () => {
            expanded.value = !expanded.value;
        };

        const formatName = (text) => {
            return text
                .replace(/-/g, ' ')
                .replace(/\b\w/g, l => l.toUpperCase());
        };

        const folders = () =>
            Object.keys(props.node).filter(k => k !== '__files');

        const files = () =>
            props.node.__files || [];

        const hasChildren = () =>
            folders().length > 0 || files().length > 0;

        return () => {
            const folderRoute = props.path.startsWith('api.') ? props.path : `api.${props.path}`;
            const folderDesc = getRouteDescription ? (getRouteDescription(folderRoute) || getRouteDescription(props.path)) : '';

            return h('div', { class: 'tree-node' }, [

                // ROW
                h('div', {
                    class: 'folder',
                    style: {
                        paddingLeft: `${props.depth * 20}px`
                    },
                    title: folderDesc || undefined
                }, [

                    // ▶ / ▼ toggle
                    h('span', {
                        style: {
                            width: '18px',
                            display: 'inline-block',
                            cursor: 'pointer'
                        },
                        onClick: toggle
                    }, hasChildren()
                        ? (expanded.value ? '▼' : '▶')
                        : '•'
                    ),

                    // [+] action icon
                    h('i', {
                        class: props.is_plus ? 'fa fa-plus-square' : 'fa fa-minus-square' ,
                        style: {
                            marginRight: '6px',
                            cursor: 'pointer',
                            color:  props.is_plus ? 'rgb(24, 175, 11)' : 'orange'
                        },
                        onClick: (e) => {
                            e.stopPropagation();

                            if (hasChildren()) {
                                const routesList = collectRoutes(props.node);
                                if (onMoveRoutes) onMoveRoutes(routesList);
                            }
                        }
                    }),

                    // name
                    h('span', {
                        style: {
                            cursor: 'pointer',
                            fontWeight: hasChildren() ? 'bold' : 'normal'
                        },
                        onClick: toggle
                    }, formatName(props.name))
                ]),

                // CHILDREN
                expanded.value && hasChildren() && h('div', {}, [

                    ...folders().map(key =>
                        h(TreeNode, {
                            name: key,
                            node: props.node[key],
                            depth: props.depth + 1,
                            path: `${props.path}.${key}`,
                            is_plus: props.is_plus
                        })
                    ),

                    ...files().map(file => {
                        const fileDesc = getRouteDescription ? getRouteDescription(file) : '';
                        return h('div', {
                            class: 'file',
                            style: {
                                paddingLeft: `${(props.depth + 1) * 20}px`
                            },
                            title: fileDesc || undefined,
                            onClick: () => {
                                if (onMoveRoute) onMoveRoute(file);
                            }
                        }, [

                            h('i', {
                                class: 'fa fa-file',
                                style: {
                                    marginRight: '6px',
                                    color: '#16a085'
                                }
                            }),

                            h('i', {
                                class: props.is_plus ? 'fa fa-plus' : 'fa fa-minus',
                                style: {
                                    marginRight: '6px',
                                    color: props.is_plus ? '#16a085' : 'red'
                                }
                            }),

                            file
                        ]);
                    })
                ])
            ]);
        };
    }
});

</script>

<style scoped>
.tree {
    font-family: Arial;
    font-size: 14px;
}

.tree-node {
    margin-top: 4px;
}

.folder {
    display: flex;
    align-items: center;
    gap: 6px;
    line-height: 24px;
}

.file {
    color: #16a085;
    cursor: pointer;
    line-height: 22px;
}
</style>