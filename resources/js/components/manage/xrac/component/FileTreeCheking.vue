<template>
    <div class="tree">
        <TreeNode
            v-for="(value, key) in tree"
            :key="key"
            :name="key"
            :node="value"
            :depth="0"
            :path="key"
        />
    </div>
</template>

<script setup>
import { computed, defineComponent, h, ref, provide, inject } from 'vue';

const props = defineProps({
    uncheckedRoutes: {type:Array, default: []},
    routes: {type:Array, default: []},
    policyControlList:{type:Array, default: []}
});

const emit = defineEmits(['update:uncheckedRoutes']);

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

const uncheckedSet = computed(() => new Set(props.uncheckedRoutes || []));
provide('uncheckedSet', uncheckedSet);

provide('toggleCheck', (targetRoutes, checkState) => {
    let newUnchecked = [...(props.uncheckedRoutes || [])];
    if (checkState) {
        // Checked: remove from uncheckedRoutes
        newUnchecked = newUnchecked.filter(r => !targetRoutes.includes(r));
    } else {
        // Unchecked: add to uncheckedRoutes
        targetRoutes.forEach(r => {
            if (!newUnchecked.includes(r)) {
                newUnchecked.push(r);
            }
        });
    }
    emit('update:uncheckedRoutes', newUnchecked);
});

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

const tree = computed(() => buildTree(props.routes));

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
        fn_action:null,
        name: String,
        node: Object,
        depth: Number,
        path: String
    },

    setup(props) {
        const expanded = ref(false);
        const onMoveRoute = inject('onMoveRoute');
        const getRouteDescription = inject('getRouteDescription');
        const uncheckedSet = inject('uncheckedSet');
        const toggleCheck = inject('toggleCheck');

        const toggle = () => {
            expanded.value = !expanded.value;
        };

        const formatName = (text) =>
            text.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

        const folders = () =>
            Object.keys(props.node).filter(k => k !== '__files');

        const files = () =>
            props.node.__files || [];

        const hasChildren = () =>
            folders().length > 0 || files().length > 0;

        const isFolderChecked = computed(() => {
            if (!uncheckedSet) return true;
            const descendants = collectRoutes(props.node);
            return descendants.every(route => !uncheckedSet.value.has(route));
        });

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

                    // expand arrow
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

                    // checkbox
                    h('input', {
                        type: 'checkbox',
                        checked: isFolderChecked.value,
                        style: {
                            marginRight: '6px',
                            cursor: 'pointer'
                        },
                        onChange: (e) => {
                            if (toggleCheck) {
                                const descendants = collectRoutes(props.node);
                                toggleCheck(descendants, e.target.checked);
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
                            path: `${props.path}.${key}`
                        })
                    ),

                    ...files().map(file => {
                        const fileDesc = getRouteDescription ? getRouteDescription(file) : '';
                        const isChecked = computed(() => {
                            return uncheckedSet ? !uncheckedSet.value.has(file) : true;
                        });

                        return h('div', {
                            class: 'file',
                            style: {
                                paddingLeft: `${(props.depth + 1) * 20}px`
                            },
                            title: fileDesc || undefined
                        }, [

                            // checkbox
                            h('input', {
                                type: 'checkbox',
                                checked: isChecked.value,
                                style: {
                                    marginRight: '6px',
                                    cursor: 'pointer'
                                },
                                onChange: (e) => {
                                    if (toggleCheck) {
                                        toggleCheck([file], e.target.checked);
                                    }
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