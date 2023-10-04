<template>
    <div class="row">
        <div class=" col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <input type="text" class=" form-control" placeholder="ຄົ້ນຫາ..." v-model="search" @keyup.enter="GetStore()">
                </div>
            </div>
            <div class="row" style=" overflow: auto; height: 68vh;" >
                <div class="col-md-3  cursor-pointer" v-for=" list in StoreData.data" :key="list.id" >
                    <div class="card mb-3" @click="AddProduct(list.id)">
                        <span class="num-list">10</span>
                    <img class="card-img-top img-cover" :src="url + '/assets/img/' + list.image" v-if="list.image" alt="image product">
                    <img class="card-img-top img-cover" :src="url + '/assets/img/no_img.jpg'" v-else alt="image product">
                    <div class="card-body p-1 text-center">
                        {{  list.name  }}
                        <p class="card-text">
                            {{ formatPrice(list.price_sell) }} ກີບ
                        </p>
               
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style=" overflow: auto; height: 80vh;">
                <div class="card-body">
                    <div>
          <label lass="form-label">ຊື່ລູກຄ້າ:</label>
          <input type="text" class="form-control mb-2" placeholder="..." >
          <label lass="form-label">ເບີໂທ:</label>
          <input type="text" class="form-control" placeholder="..." >
                        <hr>
                        <div class=" d-flex justify-content-between fs-4 text-info fw-bold">

                            <span>ລວມຍອດເງິນ:</span>
                            <span>150.000 ກີບ</span>
                        </div>

                        <button type="button" class="btn btn-info w-100 fs-5 fw-bold ">ຊຳລ່ະເງິນ</button>

    <table class="table border mt-3">
      <thead>
        <tr>
          <th>ລາຍການ</th>
          <th>ລາຄາ</th>
          <th>ຍອດລວມ</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        <tr v-for="list in listOrder" :key="list.id">
          <td>{{ list.name }}</td>
          <td class=" text-end">{{ formatPrice(list.price_sell) }} <br> {{ formatPrice(list.order_amount) }}</td>
         
          <td class=" text-end">{{ formatPrice(list.order_amount*list.price_sell) }}</td>
         
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
import { useStore } from '../store/auth';
export default {
    name: 'Minipos11Pos',
    setup(){
      const store = useStore()
      return {store}
    },
    data() {
        return {
            search:'',
            list_num: 15,
            Sort:"asc",
            StoreData:[],
            loading_table: false,
            url: window.location.origin,
            listOrder:[],
        };
    },

    mounted() {
        
    },

    methods: {
        AddProduct(id){
            //console.log(id)
            let item = this.StoreData.data.find((i)=>i.id == id)
            // console.log(item)
            let list_order_item = this.listOrder.find((i)=>i.id == id)

            if(list_order_item){

                list_order_item.order_amount++
                
            } else {
                this.listOrder.push({
                    id: item.id,
                    name: item.name,
                    order_amount: 1,
                    price_sell: item.price_sell
                })
            }


            
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
        GetStore(page){
            this.loading_table = true
            axios.get(`api/store?page=${page}&sort=${this.Sort}&list_num=${this.list_num}&search=${this.search}`,{ headers:{ Authorization: 'Bearer '+ this.store.get_token}}).then((res)=>{
                this.loading_table = false
                this.StoreData = res.data
            }).catch((err)=>{
                this.loading_table = false
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
        this.GetStore()
    },
    watch:{
        search(){
            if(this.search == ''){
                this.GetStore()
            }
        }
    }
};
</script>

<style scoped>
    .img-cover{
        width: 100%;
        height: 120px;
        object-fit: cover;
        object-position: center;
    }

    .num-list{
        position: absolute;
    background: blue;
    color: white;
    padding: 5px;
    border-radius: 0px 10px;
    right: 0px;
    }
</style>