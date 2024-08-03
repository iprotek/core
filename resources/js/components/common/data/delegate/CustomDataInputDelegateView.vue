<template>
    <div>
        <div class="card" v-if="source_id>0"> 
            <div class="card-header">CUSTOM DATA INPUTS</div> 
            <div class="card-body py-0">
                <div v-if="dataDelegateList.length == 0">
                    <code>** No Custom inputs</code>
                </div>
                <div v-for="(delItem, delIndex) in dataDelegateList" v-bind:key="'del-item-'+_uid+'-'+delItem+'-'+delIndex">
                    <div v-if="delItem.field_info">
                        <div  v-if="delItem.field_info.input_type=='date'">
                            <user-input2 v-model="delItem.value" :placeholder="delItem.placeholder" :input_style="'height:45px'" :type="'date'"></user-input2>
                        </div>
                        <div  v-else-if="delItem.field_info.input_type=='text'">
                            <user-input2 v-model="delItem.value" :placeholder="delItem.placeholder" :input_style="'height:35px'" :type="'text'"></user-input2>
                        </div>
                        <div  v-else-if="delItem.field_info.input_type=='checkbox'" class="mt-3">
                            <switch2 v-model="delItem.value"></switch2> <label class="m-0 align-middle" v-text="delItem.placeholder"></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-outline-primary" @click="save">SAVE</button>
            </div>
        </div> 
        <swal ref="swal_prompt"></swal> 
    </div>
</template>

<script>
    import BoostrapSwitch2Vue from '../../BoostrapSwitch2.vue';
    import SwalVue from '../../Swal.vue';
    import UserInput2Vue from '../../UserInput2.vue';
    export default {
        props:[ "value", "source_name", "key_name" ],
        watch: {
            value(newValue) {
                this.source_id = newValue;
            },
        },
        components: { 
            "user-input2":UserInput2Vue,
            "switch2":BoostrapSwitch2Vue,
            "swal":SwalVue
        },
        data: function () {
            return {    
                source_id:0,
                dataDelegateList:[]
            }
        },
        methods: { 
            save:function(){
                //console.log("A", ); 
                var vm = this;
                var req = {
                    key_name: this.key_name,
                    update_delegate_values: this.dataDelegateList
                };
                console.log(req);
                this.$refs.swal_prompt.alert(
                    'question', 
                    "Update Custom Data?", 
                    "Confirm" , 
                    "PUT", 
                    "/manage/iprotek-data/update-delegate-values", 
                    JSON.stringify(req)
                ).then(res=>{
                    if(res.isConfirmed){  
                        if(res.value.status == 1){ 
                            //vm.id = res.value.data.id;
                            setTimeout(function(){
                                vm.$emit('modal_updated');
                            }, 500);
                        }
                    }
                }); 
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadDataDelegate:function(){
                var vm = this;
                WebRequest2('GET', '/manage/iprotek-data/delegate-values?'+this.queryString({
                    source_id: this.source_id,
                    source_name: this.source_name,
                    key_name: this.key_name
                })).then(resp=>{
                    resp.json().then(data=>{
                        var data = data.filter(a=>a.source_type == 'input');
                         data.forEach(item => {
                            if(item.field_info && item.field_info.input_type == 'checkbox'){
                                item.value = item.value == 1;
                            }
                        });
                        vm.dataDelegateList = data;
                    });
                })
            }
        },
        mounted:function(){     
            this.source_id = this.value;
            this.loadDataDelegate();
        },
        updated:function(){

        }
    }
</script>
