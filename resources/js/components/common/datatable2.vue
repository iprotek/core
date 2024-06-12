<template>
    <div class="custom-datatable">
        <table :class="table_class" :id="'request-table'+_uid" style="width:100%">
            <thead>
                <tr>
                    <th align="left" v-for="(cols,index) in data_columns" v-bind:key="index">
                        <template v-if="cols.type==='checkbox'">
                            <input type="checkbox" class="data-table-checkall">
                        </template>
                        <span v-else v-html="cols.label"></span>
                    </th>
                </tr>
            </thead>
            <slot name="footer"></slot>
        </table>
    </div>
</template>

<script>
    const objectAssign = require('object-assign');
    export default {
        props: {
            url: {
                type: String,
            },
            columns: {
                type: [Array, Object]
            },
            createdRow: {
                type: [Function]
            },
            buttons: {
                type: Boolean,
                default: function () {
                    return true;
                }
            },
            responsive: {
                type: Boolean,
                default: function () {
                    return false;
                }
            },
            parameters: {
                type: [Array, Object],
                default () {
                    return {};
                }
            },
            fixedLeftColumns: {
                type: [String, Number],
                default () {
                    return 0;
                }
            },
            fixedRightColumns: {
                type: [String, Number],
                default () {
                    return 0;
                }
            },
            dataOnLoad: {
                type: Boolean,
                default: function () {
                    return true;
                }
            },
            selection: {
                type: Boolean,
                default () {
                    return false;
                }
            }
        },
        data: function () {
            return {
                data_columns: [],
                table: null,
                export_options: [],
                selected_ids: [],

            }
        },
        methods: {
            load_limit: function (limit) {
                this.table.page.len(limit).draw();
            },
            reload: function (clear_state) {
                if (clear_state !== false) {
                    this.table.state.clear();
                    this.table.ajax.reload();
                } else
                    this.table.ajax.reload(null, false);
            },
            remove_arr: function (array, element) {
                const index = array.indexOf(element);
                array.splice(index, 1);
            },
            init: function () {
                let vm = this;
                var dom = 'frtip';

                if (vm.table)
                    vm.table.destroy();

                let table_buttons = [];
                if (vm.buttons === true) {
                    dom = 'B' + dom;
                    table_buttons = [

                        {
                            extend: 'excel',
                            className: 'btn-xs text-uppercase',
                            autoFilter: true,
                            exportOptions: {
                                columns: vm.export_options
                            },
                        },
                        {
                            extend: 'pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            className: 'btn-xs text-uppercase',
                            exportOptions: {
                                columns: vm.export_options
                            },
                        },
                        {
                            extend: 'colvis',
                            className: 'btn-xs text-uppercase',
                        }
                    ];
                }


                vm.table = $('#request-table' + vm._uid).DataTable({
                    lengthMenu: [
                        vm.limi_values,
                        vm.limi_labels,
                    ],
                    pageLength: vm.limit_default_value,
                    scrollY: 360,
                    scrollX: true,
                    buttons: table_buttons,
                    dom: dom,
                    searching: false,
                    paging: true,
                    processing: true,
                    serverSide: true,
                    select: vm.selection,
                    order: [],
                    ajax: {
                        type: 'POST',
                        url: vm.url,
                        data: function (d) {
                            objectAssign(d, vm.parameters);
                            return d;
                        }
                    },
                    fixedColumns: {
                        leftColumns: vm.fixedLeftColumns,
                        rightColumns: vm.fixedRightColumns,
                    },
                    createdRow: vm.createdRow,
                    columns: vm.data_columns,
                    language: {
                        processing: `<div><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw text-red"></i><br><span>Loading please wait...</span></div>`,
                    }
                });

                vm.table.on('draw', function () {
                    vm.$emit('reloaded');
                });


                if (vm.selection === true) {
                    $('#request-table' + vm._uid + ' tbody').on('click', 'tr', function () {
                        var row = vm.table.row(this).data();
                        vm.$emit('rowSelected', row);
                    });

                    $('#request-table' + vm._uid + ' tbody').on('dblclick', 'tr', function () {
                        var data = vm.table.row(this).data();
                        vm.$emit('rowDoubleClicked', data);
                    });

                }
            },
            change_url(url) {
                this.table.ajax.url(url).load();
            },
            addColumn(column) {
                this.data_columns.push(column);
                this.addExportOption(column, this.data_columns.length - 1);
            },
            initColumns() {
                let vm = this;
                // Initialize first the columns
                if (vm.columns != undefined) {
                    vm.data_columns = vm.columns;
                    vm.data_columns.forEach(function (element, index) {
                        vm.addExportOption(element, index);
                    });
                }
            },
            addExportOption(column, index) {
                if (column.export != undefined) {
                    if (column.export === true)
                        this.export_options.push(index);
                } else
                    this.export_options.push(index);
            }
        },
        mounted() {
            if (this.dataOnLoad === true) {
                this.initColumns();
                this.$nextTick(function () {
                    if (this.url)
                        this.init();
                });
            }
        },
        watch: {
            url(value) {
                console.log(value);
                if (value)
                    this.init();
            }
        },
        computed: {
            table_class: function () {

                var tbl_class = 'table table-bordered';
                if (this.responsive === true)
                    tbl_class += ' responsive';

                return tbl_class;


            }
        }
    }

</script>

<style>
    .DTFC_LeftBodyLiner {
        overflow-x: hidden;
    }

    .DTFC_RightBodyLiner {
        overflow-x: hidden;
    }

</style>
