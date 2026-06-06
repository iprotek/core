<template>
    <div>
        <modal-view ref="modal" :prevent="true" :body_class="'pt-0'" :vw="(app_account_id? 50: 80)">
            <template #header >
                POLICY CONTROL
            </template> 
            <template #body >     
                <div class="py-1">
                    <div class="row" v-if="!app_account_id">
                        <div class="col-sm-5" >
                            <div class="card">
                                <div class="card-header"> <small><b> ALL POLICY CONTROLS</b></small> </div>
                                <div class="card-body">
                                    <file-tree 
                                        v-if="isLoadRoutes" 
                                        :routes="routes" 
                                        :policyControlList="policyControlList" 
                                        @move-route="moveToSelected"
                                        @move-routes="moveMultipleToSelected"
                                    />

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 text-center px-0">
                            <button class="btn btn-outline-primary btn-sm text-nowrap" @click="unloadAllPolicies">
                                <span class="fa fa-arrow-left"></span> UNSELECT ALL
                            </button>
                            <div>
                                <button class="btn btn-outline-primary btn-sm text-nowrap my-1" @click="$refs.csvFileInput.click()">
                                    IMPORT <span class="fa fa-arrow-right"></span>
                                </button>
                                <input type="file" ref="csvFileInput" accept=".csv" @change="handleCSVImport" style="display: none;" />
                            </div>
                            <button class="btn btn-outline-primary btn-sm text-nowrap" @click="exportCSV">
                               <span class="fa fa-download"></span> EXPORT SELECTED 
                            </button>
                        </div>
                        <div class="col-sm-5">
                            <div class="card">
                                <div class="card-header"> <small><b> SELECTED POLICY CONTROLS</b></small> </div>
                                <div class="card-body">
                                    <file-tree 
                                        :is_plus="false" 
                                        :routes="selectedRoutes"
                                        @move-route="moveToAll"
                                        :policyControlList="policyControlList" 
                                        @move-routes="moveMultipleToAll"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-else>
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">CURRENT POLICY CONTROL</div>
                                <div class="card-body"> 
                                    <file-tree-checking 
                                        v-if="selectedRoutes.length > 0" 
                                        :routes="selectedRoutes"
                                        :policyControlList="policyControlList"
                                        :uncheckedRoutes="uncheckedRoutes"
                                        @update:uncheckedRoutes="uncheckedRoutes = $event"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div>
                    <button v-if="app_account_id" type="button" class="btn btn-outline-secondary mr-4" @click="loadPolicy()">REVERT</button> 
                    <button type="button" class="btn btn-outline-dark mr-4" data-dismiss="modal" @click="$refs.modal.dismiss()">Close</button> 
                    <web-submit v-if="!app_account_id" el_class="btn btn-outline-primary" :action="saveRolePolicy"  :icon_class="'fa fa-save'" :label="'SAVE ROLE'"  />
                    <web-submit v-else el_class="btn btn-outline-primary" :action="saveUserRolePolicy"  :icon_class="'fa fa-save'" :label="'SAVE USER ROLE'"  />
                </div>
            </template>
        </modal-view> 
        <swal ref="swal_prompt" :set_errors="errors" @update:set_errors="errors = $event"></swal> 
    </div>

</template>

<script>    
    import WebSubmitVue from '../../../common/WebSubmit.vue';
    import FileTreeVue from '../component/FileTree.vue';
    import FileTreeCheckingVue from '../component/FileTreeCheking.vue';
    export default {
        props:[ "theme_info", "group_id", "branch_id","role_id", "app_account_id" ],
        $emits:[],
        watch: { 
        },
        components: {   
            "file-tree":FileTreeVue,
            "file-tree-checking":FileTreeCheckingVue,
            "web-submit":WebSubmitVue
        },
        data: function () {
            return {        
                promiseExec:null,
                errors:[],
                is_default:false,
                policyControlList:[],
                isLoadRoutes:true,
                routes:[
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
                ],
                initialRoutes: [],
                selectedRoutes: [],
                uncheckedRoutes:[]
           }
        },
        methods:{ 
            getRolePolicy:function(){
                var vm = this;
                let url = `/api/group/${this.group_id}/xrac/policy-control/role-routes/${vm.role_id}?branch_id=${this.branch_id}`;
                return WebRequest2('GET', url).then(resp=>{
                    if(resp.ok){
                        return resp.json().then(data=>{
                            return data;
                        });
                    }
                    return [];
                });
            },
            getUserDisablePolicy:function(){
                var vm = this;
                let url = `http://billing.iprotek.internal/api/group/${this.group_id}/xrac/policy-control/user-disable-routes/${this.app_account_id}`;
                return WebRequest2('GET', url).then(resp=>{
                    if(resp.ok){
                        return resp.json().then(data=>{
                            return data;
                        });
                    }
                    return [];
                });
            },
            saveRolePolicy:function(){
                var vm = this;
                //console.log(vm.group_id, vm.branch_id, vm.role_id, vm.app_account_id);
                var request = {
                    xrole_id: vm.role_id,
                    branch_id: vm.branch_id,
                    policy_control_routes: vm.selectedRoutes
                };
                //console.log(request);
                //return;
                return WebRequest2('POST', `/api/group/${this.group_id}/xrac/policy-control/update-role`, JSON.stringify(request)).then(resp=>{
                    return resp.json().then(data=>{
                        return data;
                    });
                })
            },
            saveUserRolePolicy:function(){
                var vm = this;
                var request = {
                    app_account_id: vm.app_account_id,
                    branch_id: vm.branch_id,
                    policy_control_routes: vm.uncheckedRoutes
                };
                return WebRequest2('POST', `/api/group/${this.group_id}/xrac/policy-control/update-user-disable-routes`, JSON.stringify(request)).then(resp=>{
                    return resp.json().then(data=>{
                        return data;
                    });
                });
            },
            reset:function(){

            },
            show:function(is_default=false){ 
                //console.log(this.group_id);
                var vm = this;
                vm.is_default = is_default;

                this.$refs.modal.show();

                
                this.loadPolicy();
                


                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
                
            },
            loadPolicy(){
                var vm = this;
                vm.isLoadRoutes = false;
                WebRequest2('GET', '/api/group/'+this.group_id+'/xrac/policy-control/list').then(resp=>{
                    if(resp.ok){
                        return resp.json().then(data=>{
                            //console.log(data);
                            vm.routes = [];
                            vm.uncheckedRoutes = [];
                            let routes = [];
                            vm.policyControlList = data;

                            
                            //LOAD ROLE POLICY
                            vm.getRolePolicy().then(selectedPolicyRoutes=>{
                                
                                data.forEach(item=>{

                                    let hasItem = selectedPolicyRoutes.filter(a=>a == item.name)[0];
                                    if(!hasItem)
                                        routes.push(item.name);

                                });
                                //console.log(routes);
                                vm.routes = routes;
                                vm.initialRoutes = data.map(item => item.name);
                                vm.selectedRoutes = selectedPolicyRoutes;


                                //LOAD USER DISABLED ROUTES
                                if(vm.app_account_id){
                                    vm.getUserDisablePolicy().then(data=>{
                                        //console.log("User disabled policy loaded.");
                                        vm.uncheckedRoutes = data;
                                        vm.isLoadRoutes = true;
                                    });
                                }
                                else{
                                    vm.isLoadRoutes = true;
                                }


                            });
                       


                        });
                    }
                });
            },
            add:function(){
                var vm = this;
                /*
                    this.$refs.swal_prompt.alert(
                        'question',
                        "Add Event", 
                        "Confirm" , 
                        "POST", 
                        "/manage/dashboard/resort-events/add", 
                        JSON.stringify(request)
                    ).then(res=>{
                        if(res.isConfirmed){
                            vm.$emit('data_updated');
                        }
                    });
                */
                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
            },
            moveToSelected(route) {
                const idx = this.routes.indexOf(route);
                if (idx > -1) {
                    this.routes.splice(idx, 1);
                }
                if (!this.selectedRoutes.includes(route)) {
                    this.selectedRoutes.push(route);
                    this.selectedRoutes.sort();
                }
            },
            moveMultipleToSelected(routesList) {
                routesList.forEach(route => {
                    const idx = this.routes.indexOf(route);
                    if (idx > -1) {
                        this.routes.splice(idx, 1);
                    }
                    if (!this.selectedRoutes.includes(route)) {
                        this.selectedRoutes.push(route);
                    }
                });
                this.selectedRoutes.sort();
            },
            moveToAll(route) {
                const idx = this.selectedRoutes.indexOf(route);
                if (idx > -1) {
                    this.selectedRoutes.splice(idx, 1);
                }
                if (!this.routes.includes(route)) {
                    this.routes.push(route);
                    this.routes.sort();
                }
            },
            moveMultipleToAll(routesList) {
                routesList.forEach(route => {
                    const idx = this.selectedRoutes.indexOf(route);
                    if (idx > -1) {
                        this.selectedRoutes.splice(idx, 1);
                    }
                    if (!this.routes.includes(route)) {
                        this.routes.push(route);
                    }
                });
                this.routes.sort();
            },
            unloadAllPolicies() {
                this.routes = [...this.initialRoutes];
                this.selectedRoutes = [];
            },
            restoreModalFocus() {
                if (window.$ && this.$refs.modal) {
                    const modalId = this.$refs.modal.modal;
                    const $modal = window.$("#" + modalId);
                    const modalData = $modal.data('bs.modal');
                    if (modalData) {
                        if (typeof modalData._enforceFocus === 'function') {
                            modalData._enforceFocus();
                        } else if (typeof modalData.enforceFocus === 'function') {
                            modalData.enforceFocus();
                        }
                    }
                }
            },
            showAlert(icon, title, message) {
                if (window.Swal) {
                    if (window.$) {
                        window.$(document).off('focusin.bs.modal');
                    }
                    window.Swal.fire({
                        icon: icon,
                        title: title,
                        text: message,
                        target: this.$refs.modal && this.$refs.modal.$el ? this.$refs.modal.$el : 'body',
                        didOpen: () => {
                            const container = window.Swal.getPopup();
                            if (container && container.parentElement) {
                                container.parentElement.style.zIndex = 100000;
                            }
                        }
                    }).then(() => {
                        this.restoreModalFocus();
                    });
                } else {
                    alert(title + ": " + message);
                }
            },
            downloadCSV(blob, filename) {
                let link = document.createElement("a");
                let url = URL.createObjectURL(blob);
                link.setAttribute("href", url);
                link.setAttribute("download", filename);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            },
            exportCSV() {
                if (!this.selectedRoutes || this.selectedRoutes.length === 0) {
                    this.showAlert('warning', 'No Selection', 'No routes are currently selected to export.');
                    return;
                }
                let csvContent = "route_name\n" + this.selectedRoutes.join("\n");
                let blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                
                let now = new Date();
                let pad = (num) => String(num).padStart(2, '0');
                let year = now.getFullYear();
                let month = pad(now.getMonth() + 1);
                let day = pad(now.getDate());
                let hours = pad(now.getHours());
                let minutes = pad(now.getMinutes());
                let seconds = pad(now.getSeconds());
                let datetime = `${year}-${month}-${day}-${hours}-${minutes}-${seconds}`;
                let defaultFilename = `policy_controls_export-${datetime}`;

                if (window.Swal) {
                    if (window.$) {
                        window.$(document).off('focusin.bs.modal');
                    }
                    window.Swal.fire({
                        title: 'Export CSV',
                        text: 'Enter a custom filename for your CSV export:',
                        input: 'text',
                        inputValue: defaultFilename,
                        target: this.$refs.modal && this.$refs.modal.$el ? this.$refs.modal.$el : 'body',
                        showCancelButton: true,
                        inputValidator: (value) => {
                            if (!value || !value.trim()) {
                                return 'Filename cannot be empty!';
                            }
                        },
                        didOpen: () => {
                            const container = window.Swal.getPopup();
                            if (container && container.parentElement) {
                                container.parentElement.style.zIndex = 100000;
                            }
                            const input = window.Swal.getInput();
                            if (input) {
                                setTimeout(() => input.focus(), 100);
                            }
                        }
                    }).then((result) => {
                        this.restoreModalFocus();
                        if (result.isConfirmed) {
                            let customName = result.value.trim();
                            if (!customName.endsWith('.csv')) {
                                customName += '.csv';
                            }
                            this.downloadCSV(blob, customName);
                        }
                    });
                } else {
                    let customName = prompt("Enter filename for CSV export:", defaultFilename);
                    if (customName === null) return; // user cancelled
                    customName = customName.trim();
                    if (!customName) {
                        customName = defaultFilename;
                    }
                    if (!customName.endsWith('.csv')) {
                        customName += '.csv';
                    }
                    this.downloadCSV(blob, customName);
                }
            },
            handleCSVImport(e) {
                let file = e.target.files[0];
                if (!file) return;

                if (file.size === 0) {
                    this.showAlert('error', 'Empty File', 'The selected file is empty.');
                    e.target.value = '';
                    return;
                }

                let reader = new FileReader();
                reader.onload = (evt) => {
                    let text = evt.target.result;
                    let lines = text.split(/\r?\n/).map(l => l.trim()).filter(l => l.length > 0);
                    if (lines.length === 0) {
                        this.showAlert('error', 'Empty File', 'The CSV file does not contain any data.');
                        e.target.value = '';
                        return;
                    }

                    // Check if first line is a header
                    let firstLine = lines[0].toLowerCase();
                    let importedRoutes = [];
                    let startIndex = 0;
                    if (firstLine.includes("route_name") || firstLine.includes("route") || firstLine.includes("name")) {
                        startIndex = 1;
                    }

                    for (let i = startIndex; i < lines.length; i++) {
                        let row = lines[i];
                        let cells = row.split(',').map(c => c.trim().replace(/^["']|["']$/g, ''));
                        if (cells[0]) {
                            importedRoutes.push(cells[0]);
                        }
                    }

                    if (importedRoutes.length === 0) {
                        this.showAlert('error', 'Invalid Format', 'No valid routes could be parsed from the CSV file.');
                        e.target.value = '';
                        return;
                    }

                    // Validate routes: they must exist in initialRoutes
                    let matchedRoutes = [];
                    let skippedRoutes = [];
                    importedRoutes.forEach(r => {
                        let trimmedRoute = r.trim();
                        if (trimmedRoute && this.initialRoutes.includes(trimmedRoute)) {
                            if (!matchedRoutes.includes(trimmedRoute)) {
                                matchedRoutes.push(trimmedRoute);
                            }
                        } else if (trimmedRoute) {
                            skippedRoutes.push(trimmedRoute);
                        }
                    });

                    // Debugging logs as requested:
                    console.log('Imported Routes:', importedRoutes);
                    console.log('Available Routes:', this.initialRoutes);
                    console.log('Matched Routes:', matchedRoutes);
                    console.log('Selected Routes:', this.selectedRoutes);

                    if (matchedRoutes.length === 0) {
                        this.showAlert('error', 'Import Failed', 'None of the imported routes match the available policy routes.');
                        e.target.value = '';
                        return;
                    }

                    // Update selectedRoutes and routes
                    // 1. Remove matchedRoutes from routes
                    this.routes = this.routes.filter(r => !matchedRoutes.includes(r));
                    // 2. Add to selectedRoutes (avoid duplicates)
                    matchedRoutes.forEach(r => {
                        if (!this.selectedRoutes.includes(r)) {
                            this.selectedRoutes.push(r);
                        }
                    });
                    this.selectedRoutes.sort();

                    if (skippedRoutes.length > 0) {
                        this.showAlert('warning', 'Import completed with warnings', `Successfully imported ${matchedRoutes.length} routes. Skipped ${skippedRoutes.length} invalid routes:\n` + skippedRoutes.slice(0, 5).join(', ') + (skippedRoutes.length > 5 ? '...' : ''));
                    } else {
                        this.showAlert('success', 'Import Successful', `Successfully imported ${matchedRoutes.length} routes.`);
                    }

                    e.target.value = '';
                };
                reader.readAsText(file);
            }

        },
        mounted:function(){      
            this.initialRoutes = [...this.routes];
        },
        updated:function(){

        }
    }
</script>
