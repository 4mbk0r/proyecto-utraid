<v-row>
    <v-col>
      {{ datos_conf }}
      {{ calendario }}
    </v-col>
  </v-row>
  <v-row>
    <v-col cols="12" sm="6">
      <v-menu
        ref="menu"
        v-model="menu"
        :close-on-content-click="false"
        :return-value.sync="dates"
        transition="scale-transition"
        offset-y
        min-width="290px"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-combobox
            v-model="dates"
            multiple
            chips
            small-chips
            label="Seleccione el Rango"
            prepend-icon="mdi-account"
            
           
            v-bind="attrs"
            v-on="on"
          ></v-combobox>
        </template>
        <v-date-picker v-model="dates" range no-title scrollable>
          <v-spacer></v-spacer>
          <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
          <v-btn text color="primary" @click="$refs.menu.save(dates)">OK</v-btn>
        </v-date-picker>
      </v-menu>
      <v-btn @click="subir_datos">
        enviar
      </v-btn>
      
          <v-timeline dense
          clipped align="start" >
    
            <v-timeline-item
              v-for="(item, i) in calendario"
              :key="i"
              :dot-color="item.color"
              class="white--text mb-12"
              color="orange"
              fill-dot

            > <template v-slot:opposite>
                <span>{{i+1}}</span>
              </template>
              <template  v-slot:icon>
                <span dark>{{item.ide}}</span>
              </template>
              <v-card >
                <v-card-title :class="['text-h6', `bg-${item.color}`]">
                  Fecha: {{item.fecha_orden}}
                  
                </v-card-title>
                <v-card-text class="bg-white text--primary">
                  
                  Fecha intervalo: {{item.fecha_inicio}} - {{item.fecha_final}}
                  <p>Intervalo {{ item.ide }}</p>
                </v-card-text>
              </v-card>
            </v-timeline-item>
          </v-timeline>
       
    </v-col>
  </v-row>