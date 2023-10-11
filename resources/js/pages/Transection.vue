<template>
    <div class="card">
  <h5 class="card-header">ລາຍການເຄື່ອນໄຫວ ທຸລະກຳ</h5>
  <div class="card-body">
    <div class="table-responsive text-nowrap">
        <div class=" d-flex justify-content-between mb-2">
            <div class=" d-flex align-items-center">
                <div class=" cursor-pointer" @click="ChacngSort()"> 
                    <!-- {{ Sort }} -->
                        <i class='bx bx-sort-up fs-4 me-2' v-if="Sort=='asc'"></i>
                        <i class='bx bx-sort-down  fs-4 me-2' v-if="Sort=='desc'"></i>
                </div>
      
                <select id="defaultSelect" v-model="list_num" @change="GetTransection()" class="form-select">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                </select>
            </div>

            <div class=" d-flex ">
                <div class="btn-group me-2" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-secondary" @click="month_type='m'"> <i class='bx bx-chevron-right' v-if="month_type=='m'"></i> ເດືອນ</button>
                    <button type="button" class="btn btn-secondary" @click="month_type='y'"> <i class='bx bx-chevron-right' v-if="month_type=='y'"></i> ປີ</button>
              </div>
              <!-- {{ dmy  }} -->
              <input type="date" v-model="dmy" class=" form-control">

            </div>

        </div>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ວັນທີ່</th>
            <th>ເລກທີ່ທຸລະກຳ</th>
            <th>ປະເພດທຸລະກຳ</th>
            <th>ລາຍລະອຽດ</th>
            <th class=" text-center">ມູນຄ່າ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="list in Transection.data" :key="list.id">
            <td>{{ date(list.created_at) }}</td>
            <td>{{ list.tran_id }}</td>
            <td>{{ list.tran_type }}</td>
            <td>{{ list.tran_detail }}</td>
            <td class=" text-end">{{ formatPrice(list.price) }} ກີບ</td>
          </tr>
        </tbody>
      </table>
      <Pagination :pagination="Transection" :offset="4" @paginate="GetTransection($event)" />
    </div>
  </div>
</div>
</template>

<script>
import axios from 'axios';
import { useStore } from '../store/auth';
import moment from "moment";
export default {
    name: 'Minipos11Transection',
    setup(){
      const store = useStore()
      return {store}
    },
    data() {
        return {
            Transection:[],
            Sort:"desc",
            list_num:10,
            month_type:'m',
            dmy: new Date().toISOString().slice(0, 10),
        };
    },

    mounted() {
        
    },
    components:{
        moment
    },
    methods: {
        date(value){
            return moment(value).format("DD/MM/YYYY")
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
        ChacngSort(){
            if(this.Sort=="asc"){
                this.Sort = "desc"
            } else {
                this.Sort = "asc"
            }
            this.GetTransection()
        },
        GetTransection(page){

            axios.post(`api/transection?page=${page}&sort=${this.Sort}&list_num=${this.list_num}`,{
                month_type: this.month_type,
                dmy: this.dmy
            },{ headers:{ Authorization: 'Bearer '+ this.store.get_token}}).then((res)=>{
                this.Transection = res.data
            }).catch((err)=>{
                console.log(err)
                if(err){
                            if(err.response.status == 401){
                                this.store.remove_token()
                                this.store.remove_user()
                                localStorage.removeItem("web_token")
                                localStorage.removeItem("web_user")
                                this.$router.push("/login")
                            }
                        }
            })
        }
    },
    created(){
        this.GetTransection()
    },
    watch:{
        dmy(){
            this.GetTransection()
        },
        month_type(){
            this.GetTransection()
        }
    },
};
</script>

<style lang="scss" scoped>

</style>