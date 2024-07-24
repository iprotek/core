<template>
    <div :class="is_test ? 'card text-red border border-danger' : 'card'">
        <div class="card-header">
          <div class="d-flex justify-content-between">
              <label class="card-title" v-text="chart_title ? chart_title : 'NO TITLE'"> </label>
              <!--<a href="javascript:void(0);">View Report</a>-->
              <button v-if="download_url" class="btn btn-outline-primary btn-sm" @click="download_click()">
                <span class="fas fa-download"></span>
                DOWNLOAD DATA
              </button>
          </div>
        </div>
        <div class="card-body">
          <div v-if="timeline" class="d-flex flex-row justify-content-end">
            <select v-model="timeline_type" @change="timeline_type_changed">
              <option value="1" v-if="days_count<=31">Day</option>
              <option value="2" v-if="days_count<=120">Week</option>
              <option value="3" v-if="days_count<=905">Month</option>
              <option value="4">Year</option>
            </select>
          </div>
          <div class="position-relative mb-4">
            <div :id="'chart-container-'+_uid">
              <canvas :id="'bar-chart-'+_uid" height="200"></canvas>
            </div>
          </div>
          <!--
          <div class="d-flex flex-row justify-content-center">
            <span v-for="(factorItem, facIndex) in factor_settings" v-bind:key="'factor'+_uid+'-'+facIndex" class="ml-1">
                <i class="fas fa-square" :style="'color:'+factorItem.backgroundColor+';'"></i> <span v-text="factorItem.title"></span>
            </span>
          </div>
          -->
        </div>
    </div>
</template>

<script> 
    export default {
        props:['type', 'chart_title', 'is_test', 'timeline', "by_value" ],
        components: {
           // "job-item":admin_jobitem 
        },
        data: function () {
            return {  
                search_text:'',
                factor_settings:[],
                labels:[],
                datasets: [],
                jsonData:{},
                days_count : 0,
                from_date:'',
                to_date:'',
                timeline_type: 0,
                download_url:""
            }
        },
        methods: {
            download_click:function(){
              window.location.href = this.download_url;
            },
            show: function () {
               // this.$refs.modal.show();
            },
            save: function () {
                var par = this; 
            }, 
            timeline_type_changed(){
              this.load_view();
            },
            load_view:function(_data = null){
              var vm = this;
              if(!_data)
                _data = JSON.parse( JSON.stringify( vm.jsonData) );
              else
                vm.jsonData = JSON.parse( JSON.stringify(_data));
              vm.factor_settings = vm.jsonData.factor_settings ? vm.jsonData.factor_settings : [];

              document.querySelector('#bar-chart-'+vm._uid).remove();
              //var canvas = document.createElement('CANVAS');
              //canvas.id = 'bar-chart-'+vm._uid;
              //canvas.height = "200";
              //document.querySelector('#chart-container-'+vm._uid).append(canvas);
              document.querySelector('#chart-container-'+vm._uid).innerHTML = '<canvas id="bar-chart-'+vm._uid+'" height="200"></canvas>';
              
              if(vm.timeline){
                _data.data = vm.timeline_breakdown(_data);
              }
              setTimeout(function(){
              vm.load_data(_data)}, 300);
            },
            timeline_breakdown:function(_data){
              //get date
              //var date_difference = '';
              var vm = this;
              
              vm.from_date = new Date(_data.from_date);
              vm.to_date = new Date(_data.to_date);
              var diffTime = Math.abs(vm.from_date -  vm.to_date);
              vm.days_count = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
              
              //SET TIME TYPE IF FROM DEFAULT 0
              if(vm.timeline_type == 0){
                if(vm.days_count <= 31)
                  vm.timeline_type = 1;
                else if(vm.days_count <= 217)
                  vm.timeline_type = 2;
                else if(vm.days_count <= 905)
                  vm.timeline_type = 3;
                else 
                  vm.timeline_type = 4;
              }
              else{
                //CONSTRAINT = ENFORCING timeline type
                if(vm.days_count > 31 && vm.timeline_type == 1)
                  vm.timeline_type = 2;
                if(vm.days_count > 217 && vm.timeline_type == 2)
                  vm.timeline_type = 3;
                if(vm.days_count > 905 && vm.timeline_type == 3)
                  vm.timeline_type = 4;                
              }
              var result_range = [];
              //vm.timeline_type = 4;
              if(vm.timeline_type == 1)
               result_range = vm.create_day_range();
              else if(vm.timeline_type == 2)
               result_range = vm.create_week_range();
              else if(vm.timeline_type == 3)
               result_range = vm.create_month_range();
              else if(vm.timeline_type == 4)
               result_range =  vm.create_year_range();

              return result_range;
            },
            create_day_range:function(){
              var vm = this;
              var from_date = vm.from_date;
              var to_date = vm.to_date;
              var factor_length = vm.jsonData.factor_settings.length;
              var date_range = [];
              while(from_date <= to_date){
                var jdata = {
                  title: from_date.dateTitle(),
                  date: from_date.dateYMD(),
                  factors:[]
                };
                date_range.push(jdata);
                for(var i=0; i< factor_length; i++){
                  jdata.factors.push(0);
                }
                from_date.addDays(1);
              }

              //SET FROM jsonData
              date_range.forEach((dateItem, dateIndex)=>{
                  vm.jsonData.data.filter(a=>a.date == dateItem.date).forEach((jItem, jIndex)=>{
                    if(vm.by_value){
                        dateItem.factors[jItem.factor_index] += (jItem.value*1);
                    }
                    else{
                        dateItem.factors[jItem.factor_index] +=1;
                    }
                  });
              });
              //console.log(date_range);

              return date_range;
            },
            create_week_range:function(){
              var vm = this;
              var day_range = vm.create_day_range();
              var week_range = []; 

              day_range.forEach((dayItem, dayIndex)=>{
                var dayDate = new Date(dayItem.date);
                //console.log(dayDate);
                var week_name = dayDate.getMonthName()+" W-"+(dayDate.getWeekOfMonth()+1);
                var week_info = week_range.filter(a=>a.title == week_name)[0];
                if(!week_info)
                {
                  week_info = {
                    title: week_name,
                    days:[dayItem.date],
                    factors: []
                  }
                  dayItem.factors.forEach((fItem, fIndex)=>{
                    week_info.factors.push(fItem);
                  })
                  week_range.push(week_info);
                }
                else{
                  week_info.days.push(dayItem.date);
                  dayItem.factors.forEach((fItem, fIndex)=>{
                    week_info.factors[fIndex] += fItem;
                  })
                }


              });
              //console.log(week_range);

              return week_range;
            },
            create_month_range:function(){
              var vm = this;
              var day_range = vm.create_day_range();
              var month_range = [];
              day_range.forEach((dayItem, dayIndex)=>{
                var dayDate = new Date(dayItem.date);
                var month_name = dayDate.getMonthName();
                month_name = month_name.slice(0, 3) + "'" + month_name.slice(3);
                var month_info = month_range.filter(a=>a.title == month_name)[0];
                if(!month_info){
                  month_info = {
                    title: month_name,
                    factors:[]
                  }
                  dayItem.factors.forEach((fItem, fIndex)=>{
                    month_info.factors.push(fItem);
                  })
                  month_range.push(month_info);
                }else{                  
                  dayItem.factors.forEach((fItem, fIndex)=>{
                    month_info.factors[fIndex] += fItem;
                  })
                }              
              });
              //console.log(month_range);
              return month_range;
            },
            create_year_range:function(){
              var vm = this;
              var day_range = vm.create_day_range();
              var year_range = [];
              day_range.forEach((dayItem, dayIndex)=>{
                var dayDate = new Date(dayItem.date);
                var year_name = dayDate.getFullYear();
                var year_info = year_range.filter(a=>a.title == year_name)[0];
                if(!year_info){
                  year_info = {
                    title: year_name,
                    factors:[]
                  }
                  dayItem.factors.forEach((fItem, fIndex)=>{
                    year_info.factors.push(fItem);
                  })
                  year_range.push(year_info);
                }else{                  
                  dayItem.factors.forEach((fItem, fIndex)=>{
                    year_info.factors[fIndex] += fItem;
                  })
                }              
              });
              //console.log(month_range);
              return year_range;


            },
            load_data:function(_data){
              'use strict'
              var vm = this;

              vm.labels = [];
              vm.datasets = [];
              
              //CREATE DATASET FIELDS
              _data.factor_settings.forEach((fItem, fIndex)=>{

                if(vm.type == 'line'){
                  vm.datasets.push({
                    type:'line',
                    data:[],
                    backgroundColor:'transparent',
                    borderColor: fItem.lineColor,
                    pointBorderColor: fItem.lineColor,
                    pointBackgroundColor:fItem.lineColor,
                    fill:false,
                    label:fItem.title
                  });
                }
                else{
                  vm.datasets.push({
                    label:fItem.title,
                    backgroundColor: fItem.backgroundColor ?? fItem.lineColor,
                    borderColor: fItem.borderColor ?? fItem.lineColor,
                    data:[]
                  })
                }
              });


              //CREATION FROM DATA
              _data.data.forEach((dItem, dIndex)=>{
                  vm.labels.push(dItem.title);
                  dItem.factors.forEach((fItem, fIndex)=>{
                    vm.datasets[fIndex].data.push(fItem);
                  });
              });
              //console.log(vm.labels, vm.datasets);

            //LABELS
            /*['For Implementation', 'For Approval','For Evaluation','For Classification' ]*/
            //DATASETS
            /*[ 
              {
                backgroundColor:'#007bff',
                borderColor:'#007bff',
                data : [1000,2000,3000,2500]
              },
              {
                backgroundColor:'#ced4da', 
                borderColor:'#ced4da',
                data:[700,1700,2700,2000]
              },
              {
                backgroundColor:'orange', 
                borderColor:'orange',
                data:[700,1700,2700,2000]
              }
            ]*/
              var ticksStyle={fontColor:'#495057',fontStyle:'bold'}
              var mode='index'
              var intersect=true
              var $salesChart = $('#bar-chart-'+vm._uid);
              var salesChart = new Chart( 
                                        $salesChart,
                                        {
                                            type : vm.type,
                                            data: {
                                                    labels : vm.labels , 
                                                    datasets: vm.datasets
                                                  },
                                            options: {
                                                    maintainAspectRatio:false,
                                                    tooltips:{
                                                      mode:mode,
                                                      intersect:intersect
                                                    },
                                                    hover:{
                                                      mode:mode,
                                                      intersect:intersect
                                                    },
                                                    legend:{
                                                      display:true
                                                    },
                                                    scales:{
                                                      yAxes:[
                                                        {
                                                          gridLines:{
                                                            display:true,
                                                            lineWidth:'4px',
                                                            color:'rgba(0, 0, 0, .2)',
                                                            zeroLineColor:'transparent'
                                                          },
                                                          ticks:$.extend({
                                                            beginAtZero:true,
                                                            callback:function(value){
                                                                
                                                                if(value>=1000)
                                                                {
                                                                  value/=1000
                                                                  value+='k'
                                                                }
                                                                return  value
                                                              }
                                                            },
                                                            ticksStyle)
                                                        }
                                                      ],
                                                      xAxes:[ 
                                                        {
                                                          display:true,
                                                          gridLines:{display:false},
                                                          ticks:ticksStyle}
                                                      ]
                                                    }
                                              }
                                        });
            }

        },
        mounted:function(){  
            //bar - vertical graph
            //horizontalBar - Horizontal Graph
          var vm = this;

          /*
          $(function(){
            vm.load_view(null);
          });
          */
        }
    }

</script>
