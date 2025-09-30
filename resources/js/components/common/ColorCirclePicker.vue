<template>  
    <span :id="colorDropdownId" style="position:relative;display:inline-block;">
        <div :id="selectedDropdownId" :title="selectedValue.description" style="cursor:pointer;display:flex;align-items:center;">
            <i class="fa fa-circle" :style="`color:${selectedValue.color}; background-color:${selectedValue.color}; border-radius:50%; border: 0.2px solid black;`"></i>
        </div>
        <div :id="optionContainer" style="display:none;position:absolute;left:0;top:100%;background:#fff;border:1px solid #ccc;box-shadow:0 4px 10px rgba(0,0,0,0.08);z-index:1000;">
            
            <div v-for="(col,colIndex) in selection_list" :title="col.description" v-bind:key="'color-opt'+col.name+'-'+colIndex" class="opt" 
                :data-color="col.color" 
                :data-name="col.name" 
                :data-description="col.description" 
                style="padding:8px 12px;cursor:pointer;display:flex;align-items:center;">
                <i class="fa fa-circle" 
                    :style="`color:${col.color}; background-color:${col.color}; border-radius:50%; border: 0.2px solid black;`"></i>
            </div>
            <!--
            <div class="opt" data-color="green" data-name="color-green" style="padding:8px 12px;cursor:pointer;display:flex;align-items:center;">
                <i class="fa fa-circle" style="color:green; background-color:green; border-radius:50%; border: 0.2px solid black;"></i>
            </div>
            <div class="opt" data-color="blue" data-name="color-blue" style="padding:8px 12px;cursor:pointer;display:flex;align-items:center;">
                <i class="fa fa-circle" style="color:blue; background-color:blue; border-radius:50%; border: 0.2px solid black;"></i>
            </div>
            -->
        </div>
    </span>
</template>
<script>
    /**
     * value: {'name':'', color:''}
     * selection_list:[{'name':'', color:''},...]
     */


    export default {
        props:[ "group_id", "value", "selection_list", "target_name", "target_id", "is_set"],
        $emits:['selected'],
        components: { 
        },
        watch: {
            value(newValue) {
                //this.color = newValue;
                this.selectedValue = {
                    name:newValue.name,
                    color:newValue.color,
                    description:newValue.description
                }
            },
        },
        data: function () {
            return {
                colorDropdownId:'color-drop-down-'+this._uid,
                selectedDropdownId:'selected-drop-down'+this._uid,
                optionContainer:'option-container-'+this._uid,
                selectedValue:{
                    name:'color-white',
                    color:'white',
                    description:''
                }
            }
        },
        methods: {
            loadCircleDropdown:function(){
                var vm = this;
                const dd = document.getElementById( this.colorDropdownId);
                const sel = document.getElementById(this.selectedDropdownId);
                const opts = document.getElementById(this.optionContainer);

                sel.addEventListener('click', function(e){
                    opts.style.display = opts.style.display === 'block' ? 'none' : 'block';
                });

                opts.addEventListener('click', function(e){

                    const opt = e.target.closest('.opt');

                    if(!opt) return;

                    const color = opt.getAttribute('data-color');
                    const name = opt.getAttribute('data-name');
                    const description = opt.getAttribute('data-description');

                    vm.selectedValue = {
                        name:name,
                        color:color,
                        description:description
                    };

                    vm.$emit('input', vm.selectedValue);
                    vm.$emit('selected', vm.selectedValue);
                    if(vm.is_set === true){
                        vm.setColorTag();
                    }

                    opts.style.display = 'none';

                });

                // close when clicking outside
                document.addEventListener('click', function(e){
                    if(!dd.contains(e.target)) opts.style.display = 'none';
                });
            },
            setColorTag:function(){
                if(this.is_set !== true) return;

                //
                let request = {
                    target_id:this.target_id,
                    target_name: this.target_name,
                    value: JSON.stringify(this.selectedValue)
                };

                WebRequest2('POST', '/api/group/'+this.group_id+'/common/tagging/set', JSON.stringify(request)).then(resp=>{
                    resp.json().then(data=>{
                        console.log(data);
                    });
                });
            }

        },
        mounted:function(){
            
            this.loadCircleDropdown(); 
            
            if(this.value){
            
                this.selectedValue = {
                    name : this.value.name,
                    color : this.value.color
                }
            }

        }
    }

</script>
