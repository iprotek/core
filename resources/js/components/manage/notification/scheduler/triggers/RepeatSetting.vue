<template>
    <div>
        <div class="card p-0">
            <div class="card-header"> REPEAT DETAILS </div>
            <div class="card-body p-0">
                <div class="mx-4">
                    <label class="mb-0 mt-2">TYPE:</label>
                    <select @change="value_changed" v-model="repeat_type" class="form-control">
                        <option value="yearly">YEARLY</option>
                        <option value="monthly">MONTHLY</option>
                        <option value="weekly">WEEKLY</option>
                        <option value="daily">DAILY</option>
                        <option value="datetime">NO REPEAT | ON DATETIME SCHED</option>
                    </select>
                </div>
                <div class="mx-4" v-if=" repeat_type != 'daily' && repeat_type != 'datetime' " >
                    <input2 @value_changed="value_changed" v-model="repeat_days_after" :placeholder="'Repeat days after trigger'" :input_style="'height:35px;'" :placeholder_description="'The number days this notificatin will repeat.'" />
                </div>
                <table class="table w-100">
                    <tr v-if="repeat_type == 'yearly'">
                        <td class="text-right">
                            <label class="mb-0">MONTH:</label>
                        </td>
                        <td >
                            <select @change="value_changed" class="form-control" v-model="repeat_info.month_name">
                                <option v-for="(mo,moIn) in monthList" :value="mo.value" v-bind:key="'yearly-'+mo.value+'-'+moIn">{{ mo.name  }}</option>
                            </select>
                        </td>
                    </tr> 
                    <tr v-if="repeat_type == 'monthly' || repeat_type == 'yearly'">
                        <th class="text-right align-top">DAY:</th>
                        <td>
                            <input @change="value_changed" class="form-control" v-model="repeat_info.month_day"/>
                            <small class="text-secondary">Accepted value: 1 to 25 </small>
                        </td>

                        
                    </tr>
                    <tr v-if="repeat_type == 'weekly'">
                        <th class="text-right">WEEK:</th>
                        <td>
                            <select @change="value_changed" class="form-control" v-model="repeat_info.week_day" >
                                <option v-for="(we,wekIn) in weekDayList" :value="we.value" v-bind:key="'weekly-'+we.value+'-'+wekIn">{{ we.name  }}</option>
                            </select>
                        </td>
                    </tr>
                    <tr v-if="repeat_type == 'daily'">
                        <td></td>
                        <td></td>
                    </tr>
                    <tr v-if="repeat_type == 'datetime'">
                        <th class="text-right">DATETIME:</th>
                        <td><input @change="value_changed" v-model="repeat_info.datetime" class="form-control" type="datetime-local"/></td>
                    </tr>

                    <tr v-if="repeat_type != 'datetime'">
                        <th class="text-right">TIME:</th>
                        <td>
                            <input @change="value_changed" class="form-control" v-model="repeat_info.time" type="time" />
                        </td>
                    </tr>
                </table>
            
            </div>
        </div>
    </div>
</template>

<script>
    import UserInput2 from '../../../../common/UserInput2.vue';
    export default {
        props:[ "set_repeat_type", "set_repeat_info" ],
        emits:[ "update:set_repeat_type", "update:set_repeat_info"],
        watch: { 
            set_repeat_type:function(newVal){
                this.repeat_type = newVal ? newVal : 'yearly';
            },
            set_repeat_info:function(newVal){
                if(!newVal){
                    newVal = {
                        month_name:'Jan',
                        month_day:1,
                        week_day:'Sun',
                        datetime:'',
                        time:''
                    };
                }


                this.repeat_info = {
                    month_name: newVal.month_name,
                    month_day: newVal.month_day,
                    week_day: newVal.week_day,
                    datetime: newVal.datetime,
                    time: newVal.time,
                };
            }
        },
        components: { 
            "input2":UserInput2
        },
        data: function () {
            return { 
                repeat_type:'yearly',
                repeat_days_after:0,
                repeat_info:{
                    month_name:'Jan',
                    month_day:1,
                    week_day:'Sun',
                    datetime:'',
                    time:''
                },
                monthList:[
                    {name:'January', value:'Jan'},
                    {name:'February', value:'Feb'},
                    {name:'March', value:'Mar'},
                    {name:'April', value:'Apr'},
                    {name:'May', value:'May'},
                    {name:'June', value:'Jun'},
                    {name:'July', value:'Jul'},
                    {name:'August', value:'Aug'},
                    {name:'September', value:'Sep'},
                    {name:'October', value:'Oct'},
                    {name:'November', value:'Nov'},
                    {name:'December', value:'Dec'}
                ],
                weekDayList:[
                    {name:'Sunday', value:'Sun'},
                    {name:'Monday', value:'Mon'},
                    {name:'Tuesday', value:'Tue'},
                    {name:'Wednesday', value:'Wed'},
                    {name:'Thursday', value:'Thu'},
                    {name:'Friday', value:'Fri'},
                    {name:'Saturday', value:'Sat'},
                ]
                
            }
        },
        methods: { 
            value_changed:function(){
                this.$emit('update:set_repeat_type', this.repeat_type);
                this.$emit('update:set_repeat_info', this.repeat_info);
            },
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
