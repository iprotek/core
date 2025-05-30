<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <label class="mb-0">Schedule List</label>
                    </div>
                    <div class="card-body">
                        <table class="w-100 custom-red-table">
                            <thead>
                                <tr>
                                    <th style="width:100px;">Ref#</th>
                                    <th>Name</th>
                                    <th>Triggers</th>
                                    <th>IsActive</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="isLoading">
                                    <td class="text-center text-danger" colspan="5"> -- LOADING SCHEDULES -- </td>
                                </tr>
                                <tr v-else-if="scheduleList.length == 0">
                                    <td class="text-center text-danger" colspan="5"> -- NO  SCHEDULES FOUND -- </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PageFooterVue from '../../../common/PageFooter.vue';
    export default {
        props:[  ],
        components: { 
        },
        watch: { 
        },
        data: function () {
            return {
                isLoading:false,
                scheduleList:[],
                schedulePageData:null
            }
        },
        methods: { 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadingScheduleList:function(){

            }
        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
