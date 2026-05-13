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
import { computed, defineComponent, h, ref } from 'vue';

const routes = [
    "api.cms.save",
    "api.collector.batch.add",
    "api.collector.batch.get",
    "api.collector.batch.list",
    "api.collector.branch.add",
    "api.collector.branch.collection-list",
    "api.collector.branch.get",
    "api.collector.branch.list",
    "api.collector.branch.my-collection-dashboard",
    "api.collector.branch.remove",
    "api.collector.branch.settings.get",
    "api.collector.branch.settings.set",
    "api.collector.collection.calendar.my-collection-events",
    "api.collector.collection.calendar.my-collections",
    "api.collector.collection.due.list",
    "api.collector.collection.google-map.find-subscriber",
    "api.data-model.model-fields.field.add",
    "api.data-model.model-fields.field.remove",
    "api.data-model.model-fields.field.update",
    "api.data-model.model-fields.index"
];

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

const tree = computed(() => buildTree(routes));

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
        name: String,
        node: Object,
        depth: Number,
        path: String
    },

    setup(props) {
        const expanded = ref(false);

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

        const onCheck = (e) => {
            e.stopPropagation();

            if (hasChildren()) {
                console.log("FOLDER MEMBERS:", collectRoutes(props.node));
            } else {
                console.log("ROUTE:", props.path);
            }
        };

        return () => {

            return h('div', { class: 'tree-node' }, [

                // ROW
                h('div', {
                    class: 'folder',
                    style: {
                        paddingLeft: `${props.depth * 20}px`
                    }
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

                    // checkbox (NEW)
                    h('input', {
                        type: 'checkbox',
                        style: {
                            marginRight: '6px',
                            cursor: 'pointer'
                        },
                        onClick: onCheck
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

                    ...files().map(file =>
                        h('div', {
                            class: 'file',
                            style: {
                                paddingLeft: `${(props.depth + 1) * 20}px`
                            }//,
                            //onClick: () => console.log("ROUTE:", file)
                        },[

                            // checkbox (NEW)
                            h('input', {
                                type: 'checkbox',
                                style: {
                                    marginRight: '6px',
                                    cursor: 'pointer'
                                },
                                onClick: () => console.log("ROUTE:", file)
                            }),
                            
                            file
                        ])
                    )
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