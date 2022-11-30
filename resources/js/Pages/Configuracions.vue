<template>
     <div>
      <div>--- {{ configuracion }}</div>
      <div>--- {{ calendario }}</div>
    <div class="text-center d-flex pb-4">
      <v-btn @click="all">
        all
      </v-btn>
      
      <v-btn @click="none">
       
      </v-btn>
    </div>

    <v-expansion-panels
      v-model="panel"
      multiple
    >
      <v-expansion-panel
        v-for="(item,i) in calendario"
        :key="i"
      >
        <v-expansion-panel-header> 
          <v-row align="center"
                class="spacer"
                no-gutters>
            <v-col>
              {{ item.descripcion }} {{ item.fecha_inicio }} - <p v-if="item.fecha_final='9999-12-30'">Indefinido</p><p v-else>{{ item.fecha_final }}</p>
            </v-col>
          </v-row>
        </v-expansion-panel-header>
        
          
          <v-expansion-panel-content>
          <span>{{item.descripcion}}</span>
          <span>{{item.atencion}}</span>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
  </div>
</template>
    
<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Sala from '@/Pages/Micomponet/Sala'
import moment from 'moment'
export default {
    props:{
      configuracion: Array,
      calendario: Array
    },  
    data () {
      return {
        panel: [],
        items: 5,
      }
    },
    methods: {
      // Create an array the length of our items
      // with all values as true
      all () {
        this.panel = [...Array(this.items).keys()].map((k, i) => i)
      },
      // Reset the panel
      none () {
        this.panel = []
      },
    },
  }
</script>
    