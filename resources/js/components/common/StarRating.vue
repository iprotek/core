<template>    
    <div>
        <div>
                <label class="p-0 m-0 text-xs" v-text="' ( '+DynamicRate+' / '+starCount+' ) '"></label>
        </div>
        <div>
            <div :class="'rate-'+_uid+' star rating p-0 '+addclass">
                <template v-for="item  in star">
                    <input @change="rate_changed" :disabled="disabled" v-model="rate" type="radio" :id="'star'+item+'-'+_uid" :name="'rating-'+_uid" :value="item" v-bind:key="'star-input-'+_uid+'-'+item" />
                    <label class="p-0 m-0" :for="'star'+item+'-'+_uid" :title="item+' star'" v-bind:key="'star-label-'+_uid+'-'+item"></label>
                </template>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props:[ "starCount", "startRate","disabled","addclass" ],
        components: { 
        },
        data: function () {
            return { 
                final_starCount:5,
                star: [5,4,3,2,1],
                rate: 0,
                DynamicRate:0,
            }
        },
        methods: {
            show: function () {
               // this.$refs.modal.show();
            }, 
            rate_changed(){
                this.DynamicRate = this.rate;
            },
            save: function () { 
            },
            load_languages(){ 
                //console.log(vm.languages);
            },
            load_table(){ 
                //vm.dataTable = new DataTable('#table-'+vm._uid, vm.json);
            },
            reload_table(){ 
                //$('#table-'+vm._uid).DataTable(vm.json);
            }

        },
        mounted:function(){     
            var vm = this; 
            vm.DynamicRate = vm.startRate;
            if(!vm.starCount*1){
                vm.final_starCount = 5;
            }else{
                vm.final_starCount = vm.starCount*1;
            }

            vm.star = [];
            var star = [];
            for(var i = vm.final_starCount; i>0; i--){
                star.push(i);
            }
            vm.star = star;
            
            //Constraint of Max star
            if(vm.startRate * 1){
                var rate = (vm.startRate*1).toFixed(0);
                if(rate<1)
                    rate = 1;
                if(rate>vm.starCount)
                    rate = vm.starCount;
                vm.rate = rate;
            }
            //$('#table-'+vm._uid).DataTable(vm.json);
            
            const ratingInputs = document.querySelectorAll('.rate-'+vm._uid+'.rating input');
            function handleRatingChange() {
                const selectedRating = this.value;
                console.log('Selected rating:', selectedRating);
                // You can perform further actions with the selected rating here
            }

            ratingInputs.forEach((input) => {
                input.addEventListener('change', handleRatingChange);
            });
            
        }
    }
    
</script>
