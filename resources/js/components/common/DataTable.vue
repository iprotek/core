<template>
    <div class="card mt-1 p-2" style="overflow:auto;width:100%;">
        <table :id="'table-'+_uid" class="mt-1">
            <thead>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
                <tr>
                    <th v-for="(col, colIndex ) in columns" v-bind:key="'col-foot-'+_uid+'-'+colIndex" v-text="col.title ? col.title : col.data"> </th>
                </tr>
            </tfoot>
        </table>
    </div>
</template>
<script> 
    //import DataTable from 'datatables.net-dt';
    export default {
        props:[ "json" ],
        components: { 
        },
        data: function () {
            return {    
                columns:[],
                dataTable:null
            }
        },
        methods: {
            show: function () {
               // this.$refs.modal.show();
            }, 
            save: function () { 
            },
            load_languages(){ 
                //console.log(vm.languages);
            },
            load_table(){
                var vm = this;
                vm.dataTable = $('#table-'+vm._uid).DataTable(vm.json);
                //vm.dataTable = new DataTable('#table-'+vm._uid, vm.json);
                
                document.querySelectorAll('.dataTable').forEach((el)=>{
                    el.classList.add('w-100');
                });
            },
            reload_table(url=null){
                var vm = this;
                if(!url)
                    vm.dataTable.ajax.reload();
                else
                    vm.dataTable.ajax.url( url ).load();
                //$('#table-'+vm._uid).DataTable(vm.json);
                document.querySelectorAll('.dataTable').forEach((el)=>{
                    el.classList.add('w-100');
                });
            }

        },
        mounted:function(){     
            var vm = this;
            vm.columns = vm.json.columns;
            vm.json.responsive = true;
            this.load_table();
            //$('#table-'+vm._uid).DataTable(vm.json);
        }
    }
</script>
