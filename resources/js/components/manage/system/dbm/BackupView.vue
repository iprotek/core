<template>
    <div>
        <!--
        <a :href="'/api/group/'+group_id+'/system/dbm/create-backup'" class="btn btn-outline-primary btn-sm">
            CREATE BACKUP
        </a>
        -->
        <div @click="downloadClick"  class="btn btn-outline-primary btn-sm">
            CREATE BACKUP
        </div>
        <div class="mt-1"> 
            <page-datatable 
                ref="backup_page"
                :is_use_top_search="false" 
                :is_loading="isLoading" 
                :items="backupItems"
                :url="url"
                :search_placeholder="'Search Scheduler'"
                :json_filter="filters"
                
                @update:items="backupItems = $event"
                @update:is_loading="isLoading = $event"
            >
                <table class="w-100 custom-red-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:100px;">Ref#</th>
                            <th class="text-center">Created At</th>
                            <th>Status</th>
                            <th>is Auto</th>
                            <th>is Completed</th>
                            <th>By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="isLoading">
                            <td class="text-center text-danger" colspan="6"> -- LOADING Backups -- </td>
                        </tr>
                        <tr v-else-if="backupItems.length == 0">
                            <td class="text-center text-danger" colspan="6"> -- NO  Backups FOUND -- </td>
                        </tr>
                        <tr v-for="(item,itemIndex) in backupItems" v-bind:key="'item-sched-'+item.id+'-'+itemIndex">
                            <th v-text="item.id"></th>
                            <td v-text="item.created_at"></td>
                            <td v-text="item.status_info"></td>
                            <td>
                                <span v-if="item.is_auto" class="text-primary">Auto</span>
                                <span v-else class="text-danger">Manual</span>
                            </td>
                            <td>
                                <span v-if="item.is_completed" class="text-primary">Yes</span>
                                <span v-else class="text-danger">No</span>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

            </page-datatable>
        </div>
    </div>
</template>

<script>
    import PageDataTableVue from '../../../common/PageDataTable.vue';
    export default {
        props:[ "group_id", "branch_id" ],
        $emits:[],
        watch: { 
        },
        components: { 
            "page-datatable":PageDataTableVue
        },
        data: function () {
            return { 
                current_page:1,
                isLoading:false,
                backupItems:[],
                url:"/api/group/"+this.group_id+"/system/dbm/backup-list",
                filters:{}
            }
        },
        methods: { 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            downloadClick:function(){
                var vm = this;
                return fetch('/api/group/'+this.group_id+'/system/dbm/create-backup').then(response=>{
                // Check if request was successful
                    if (!response.ok) {
                        response.json().then(data=>{
                            alert(data.message);
                        });
                        return;
                    }
                    response.blob().then(data=>{
                        
                        const blob = data;
                    
                        const link = document.createElement("a");
                        link.href = URL.createObjectURL(blob);
                        link.download = "backup-"+vm.getCurrentDateTime()+".sql";  // You can change filename
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        URL.revokeObjectURL(link.href);

                    });
                    vm.$refs.backup_page.reloadPage();
                });
            },
            getCurrentDateTime: function() {
                const now = new Date();

                const Y = now.getFullYear();
                const m = String(now.getMonth() + 1).padStart(2, '0');     // Month is 0-based
                const d = String(now.getDate()).padStart(2, '0');

                let h = now.getHours();
                const i = String(now.getMinutes()).padStart(2, '0');
                const s = now.getSeconds();

                // Convert to 12-hour format (h) — optional
                h = h % 12 || 12;
                h = String(h).padStart(2, '0');

                return `${Y}${m}${d}${h}${i}${s}`;
            }

        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
