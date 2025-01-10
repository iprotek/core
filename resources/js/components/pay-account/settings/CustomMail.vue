<template>
    <div > 
        <div class="row" >
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Mail Settings</h4>
                    </div>
                    <div class="card-body">
                        <div>
                            <label class="mb-0">SMTP Setup:</label>
                            <div>
                                <div> 
                                    <div class="ml-2">
                                        <label class="mb-0 text-primary" >Sender Name: </label> <small v-if="is_default == 1"><i>Used in Default</i></small>
                                        <input class="form-control" v-model="name"/>
                                    </div>
                                    <div class="ml-2">
                                        <label class="mb-0 text-primary" >Reply To: </label> <small v-if="is_default == 1"> <i>Used in Default </i></small>
                                        <input class="form-control" v-model="reply_to"/>
                                    </div>
                                    <div class="ml-2">
                                        <label class="mb-0" >Transport: </label>
                                        <input class="form-control" v-model="transport"/>
                                    </div>
                                    <div class="ml-2">
                                        <label class="mb-0" >Host: </label>
                                        <input class="form-control" v-model="host"/>
                                    </div>
                                    <div class="ml-2">
                                        <label class="mb-0" >Port: </label>
                                        <input class="form-control" v-model="port"/>
                                    </div> 
                                    <div class="ml-2">
                                        <label class="mb-0" >Encryption: </label>
                                        <input class="form-control" v-model="encryption"/>
                                    </div>
                                    <div class="ml-2">
                                        <label class="mb-0" >Username:</label>
                                        <input class="form-control" v-model="username"/>
                                    </div>
                                    <div class="ml-2">
                                        <label class="mb-0" >Password:</label>
                                        <input class="form-control" v-model="password" type="password"/>
                                    </div>
                                    <label >Status: 
                                    </label>
                                    <label class="text-secondary"  v-if="is_default == 1">DEFAULT MAILER </label>
                                    <label v-else class="text-primary"> CUSTOM MAILER  </label>
                                </div>
                                <button class="btn btn-outline-primary mt-3 ml-2" @click="setup_smtp(0)">
                                      Update to Custom
                                </button>
                                <button class="btn btn-outline-secondary mt-3 ml-2" @click="setup_smtp(1)">
                                      Update to Default
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <swal ref="swal_prompt"></swal> 
    </div>
</template>

<script>
    export default {
        props:[ "group_id" ],
        components: { 
        },
        data: function () {
            return {    
                name:'',
                reply_to:'',
                transport:'smtp',
                host:'',
                port:'',
                encryption:'',
                username:'',
                password:'',
                is_default:1,
            }
        },
        methods: { 
            get_smtp:function(){
                WebRequest2('GET', '/api/group/'+this.group_id+'/settings/smtp-connection/get' ).then(resp=>{
                    resp.json().then(data=>{
                        //console.log(data);
                        this.name = data.name;
                        this.reply_to = data.reply_to;
                        this.transport = data.transport;
                        this.host = data.host;
                        this.port = data.port;
                        this.encryption = data.encryption;
                        this.username = data.username;
                        this.password = data.password;
                        this.is_default = data.is_default;
                    });
                })
            },
            setup_smtp:function(is_default=1){
                var req = {
                    name: this.name,
                    reply_to: this.reply_to,
                    transport: this.transport,
                    host: this.host,
                    port: this.port,
                    encryption: this.encryption,
                    username: this.username,
                    password: this.password,
                    is_default: is_default
                };
                this.$refs.swal_prompt.alert(
                    'question', 
                    "USE "+(is_default == 1?"DEFAULT":"CUSTOM"), 
                    "Confirm" , 
                    "POST", 
                    '/api/group/'+this.group_id+'/settings/smtp-connection/setup', 
                    JSON.stringify(req)
                ).then(res=>{ 
                    if(res.isConfirmed){
                        if(res.value.status == 1){
                            this.is_default = is_default;
                        }else{
                            this.is_default = 1;
                        }
                    }
                }); 
                //WebRequest2('POST', '/api/group'+this.group_id+'/settings/smtp-connection/setup',  JSON.stringify(req)).then(rep)
            }

        },
        mounted:function(){  
            this.get_smtp();   
        },
        updated:function(){

        }
    }
</script>
