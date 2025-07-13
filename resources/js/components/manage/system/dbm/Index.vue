<template> 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">DB Management</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4"> 
                            <p>DB Management information and details here..</p>
                        </div>
                        <div class="col-sm-8">

                            <div class="accordion" :id="'accordion-container-'+_uid">
                                <accordion :accordionContainerId="'accordion-container-'+_uid">
                                    <template slot="header">
                                        <b>Backup</b> 
                                    </template>
                                    <template slot="body"> 
                                        <div>
                                            <backup-view :branch_id="branch_id" :group_id="group_id"/>
                                        </div>
                                    </template>
                                </accordion>
                                <accordion :accordionContainerId="'accordion-container-'+_uid">
                                    <template slot="header">
                                        <b>Restore</b>
                                    </template>
                                    <template slot="body"> 
                                        <div>
                                            <restore-view :branch_id="branch_id" :group_id="group_id"/>
                                        </div>
                                    </template>
                                </accordion>
                                <accordion :accordionContainerId="'accordion-container-'+_uid">
                                    <template slot="header">
                                        <b> <span class="fa fa-cogs"></span>  Settings</b>
                                    </template>
                                    <template slot="body"> 
                                        <div class="mt-1"> 
                                            <div class="btn btn-outline-warning btn-sm">
                                                <span class="fa fa-cogs"></span> SETTINGS
                                            </div> 
                                        </div>
                                    </template>
                                </accordion>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</template>

<script>
    
    import AccordionVue from '../../../common/Accordion.vue';
    import BackupViewVue from './BackupView.vue';
    import RestoreViewVue from './RestoreView.vue';

    export default {
        props:[ "group_id", "branch_id" ],
        $emits:[],
        watch: { 
        },
        components: { 
            "accordion":AccordionVue,
            "backup-view":BackupViewVue,
            "restore-view":RestoreViewVue
        },
        data: function () {
            return { 

            }
        },
        methods: { 
            
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },

        },
        mounted:function(){     
        },
        updated:function(){

        }
    }
</script>
