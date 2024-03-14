<template>
  <v-dialog v-model="dialog" ref="ventana" fullscreen hide-overlay @keydown.enter.stop.prevent="onEnter" persistent
    class="fill-height" color="blue" transition="dialog-bottom-transition">
    <v-toolbar :color="op1 === 1 ? 'green' : 'blue'">
      <v-btn icon @click="close">
        <v-icon>mdi-close</v-icon>
      </v-btn>
      <v-toolbar-title>Agendar Cita</v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>

    <v-card>
      <!--<v-tabs :color="op1 === 1 ? 'green' : 'blue'" v-model="datos_informacion" icons-and-text centered>
        <v-tab :color="op1 === 1 ? 'errror' : 'blue'" key="0">
          {{ op1 === 1 ? "Paciente Nuevo" : "Paciente" }}
          <v-icon color="poobrown">{{ icon_ci }}</v-icon>
        </v-tab>
      </v-tabs>-->

      <!--<v-tabs-items v-model="datos_informacion" touchless>-->


      <v-card>
        <v-form class="pa-4" v-model="valid" ref="formDatopersonales">
          <v-row>

            <v-col cols="7">
              <v-container class="teal lighten-3 imprimir-visible" align="center" justify="center">
                <v-row class="pa-0 ma-0">
                  <v-col class="pa-0 ma-0 d-flex align-center justify-center" outlined tile cols="2">
                    <img height="80" width="100" ref="seccionprint" contain src="assets/logo-sedes-lapaz.png" />
                  </v-col>
                  <v-col class="pa-0 ma-0 tamano-letra" align="center" cols="8" style="font-size: 10px !important;">
                    <v-card>
                      <p class="subtitle-2 pa-0 ma-0">Gobierno Autónomo Departamental de La Paz</p>
                      <p class="subtitle-3 pa-0 ma-0">SERVICIO DEPARTAMENTAL DE SALUD
                        UNIDAD DE TRATAMIENTO, REHABILITACION, INVESTIGACION SOCIAL EN DROGODEPENDENCIAS Y
                        DISCAPACIDAD</p>
                      <p class="subtitle-3 pa-0 ma-0">UTRAID - LA PAZ</p>
                    </v-card>
                  </v-col>
                  <v-col class="pa-0 ma-0 d-flex align-center justify-center" cols="2">
                    <img height="80" width="100" contain src="assets/GOBIERNO.png" />
                  </v-col>
                </v-row>
                <v-row class="pa-0 ma-0">
                  <v-col class="pa-1 ma-0 " cols="4" align="center" justify="center">

                    <v-row class="pa-0 ma-0">
                      <v-col cols="12" sm="8" class="pa-0 ma-0">
                        <v-text-field v-model="paciente.ci" :rules="ciRules" :disabled="op1 === 2"
                          label="Cedula de Identidad" @keydown.enter="buscadorporvalor()" @input="(v) => {
                            paciente.ci = v.toUpperCase().trim();
                          }" required dense solo>
                        </v-text-field>
                      </v-col>
                      <v-col class="pa-0 ma-0" cols="12" sm="4">
                        <v-select v-model="paciente.expedido" :rules="nombreRules" persistent-placeholder
                          :disabled="op1 === 2" placeholder="No se tiene datos" :items="departamentos" label="Expedido"
                          @change="buscadorporci()" dense solo>
                        </v-select>
                      </v-col>
                    </v-row>

                  </v-col>

                  <v-col class="pa-1 ma-0" cols="4" align="center" justify="center">
                    <p class="pa-1 ma-0 tamano-letra">PROGRAMACION PARA EVALUACION DE DISCAPACIDAD</p>
                  </v-col>

                  <v-col class="pa-1 ma-0 " cols="4" align="center" justify="center">
                    <div class="centered-select">
                      <v-select v-model="antiguedadpaciente" :items="options" label="Selecciona una opción"
                      class="text-center-select text-h6   " :menu-props="{ auto: true }" hide-details dense
                        solo></v-select>
                    </div>
                    <!--<p class="pa-1 ma-0 tamano-codigo"> NUEVO </p>-->
                  </v-col>
                </v-row>
                <v-row class="pa-2 ma-0">
                  <v-col class="pa-0 ma-0" outlined align="center" justify="center">
                    <v-card class="subtitle-1">
                      <v-row>
                        <v-col cols="12" sm="4" md="4">
                          <v-text-field v-model="paciente.nombres" :rules="nombreRules" label="Nombres" @input="(v) => {
                            paciente.nombres = v.toUpperCase().trim();
                          }" @change="buscadorporvalor()" required>
                          </v-text-field>
                        </v-col>
                        <v-col cols="12" sm="4" md="4">
                          <v-text-field v-model="paciente.ap_paterno" @input="(v) => {
                            paciente.ap_paterno = v.toUpperCase().trim();
                            validar_apellido(v)
                          }" :error-messages="errorpaterno" @keydown.enter="buscadaorporvalor()"
                            @change="buscadorporvalor()" label="Apellido Paterno" required>
                          </v-text-field>
                        </v-col>
                        <v-col cols="12" sm="4" md="4">
                          <v-text-field v-model="paciente.ap_materno" @input="(v) => {
                            paciente.ap_materno = v.toUpperCase().trim();
                            validar_apellido(v)
                          }" :error-messages="errormaterno" @keydown.enter="buscadorporvalor()"
                            @change="buscadorporvalor()" label="Apellido Materno" required>
                          </v-text-field>
                        </v-col>

                      </v-row>
                      <v-row no-gutters>
                        <v-col cols="12" sm="1" md="1">
                          <v-icon @click="ver_apellido_casada = !ver_apellido_casada">
                            mdi-human-male-female
                          </v-icon>

                        </v-col>
                        <v-col v-show="ver_apellido_casada" cols="12" sm="4" md="4">
                          <v-text-field v-model="paciente.ap_casada" @input="(v) => {
                            paciente.ap_casada
                              = v.toUpperCase();
                          }
                            " @keydown.enter="buscadorporvalor()" @change="buscadorporvalor()" label="Apellido Casado"
                            required>
                          </v-text-field>
                        </v-col>
                      </v-row>
                      <v-row no-gutters>
                        <v-col cols="12" sm="4" md="4">
                          <v-text-field v-model="paciente.correo" label="Correo">
                          </v-text-field>
                        </v-col>
                        <v-col cols="12" sm="4" md="4">
                          <v-text-field v-model="paciente.celular" label="Numero Celular">
                          </v-text-field>
                        </v-col>
                        <v-col cols="12" sm="4" md="4">
                          <v-text-field v-model="paciente.direccion" label="Direccion">
                          </v-text-field>
                        </v-col>
                      </v-row>
                      <v-row no-gutters>
                        <v-col cols="12" sm="6">
                          <v-text-field v-model="paciente.fecha_nacimiento" :min="minFechaNac" :max="maxFechaNac"
                            type="date" label="Fecha de nacimiento">
                          </v-text-field>
                        </v-col>
                        <v-col cols="12" class="d-flex justify-center" sm="6">
                          <!--<v-select v-model="paciente.sexo" persistent-placeholder placeholder="No se tiene datos"
                      :items="ssexo" color="purple darken-3" label="Sexo" pa-0 solo>
                    </v-select>-->

                          <v-radio-group v-model="paciente.sexo" row>
                            <v-radio label="MASCULINO" value="MASCULINO"></v-radio>
                            <v-radio label="FEMENINO" value="FEMENINO"></v-radio>
                          </v-radio-group>
                        </v-col>

                      </v-row>


                    </v-card>
                  </v-col>

                </v-row>
                <template v-if="con_cita">

                  <v-row class="pa-2 ma-0">

                    <v-col class="pa-0 ma-0" cols="12" outlined tile align="center" justify="center">
                      <v-card v-if="!con_cita">
                        Lugar: {{ getlugar(cita_nueva.lugar) }}
                      </v-card>
                      <v-card v-else dense>
                        <v-autocomplete v-model="cita_nueva.institucion" :items="lugares" @change="actualizadorLugar()"
                          no-data-text="No se encontraron resultados" item-text='direccion' item-value="codigo">
                        </v-autocomplete>
                      </v-card>
                    </v-col>
                  </v-row>
                  <v-row class="pa-2 ma-0">
                    <v-col class="pa-0 ma-0" cols="8" outlined tile align="center" justify="center">

                      <v-card v-if="!con_cita">
                        Fecha de evaluacion: {{ fechaTexto(cita_nueva.fecha) }}
                      </v-card>
                      <v-card v-else dense>

                        <input v-model="cita_nueva.fecha" @blur="actualizarCita"
                        v-on:keypress.enter="actualizarCita" 
                        type="date" class="pa-2 ma-2">


                      </v-card>
                    </v-col>
                    <v-col class="pa-0 ma-0" cols="4" outlined tile align="center" justify="center">
                      <v-card v-if="!con_cita">
                        Hora: {{ horaTexto(cita_nueva.hora_inicio) }}
                      </v-card>
                      <v-card v-else dense>
                        <v-select v-model="cita_nueva.id_horario" :items="horarios" class="pa-2 ma-0"
                        item-text='hora_inicio' item-value="id" 
                        no-data-text="Cupos llenos"
                        
                        >
                        </v-select>
                      </v-card>
                    </v-col>

                  </v-row>
                  <v-row class="pa-2 ma-0">
                    <v-col class="pa-2 ma-0" align="center" justify="center">

                      <v-textarea class="pa-0 ma-0" ref="textpri" label="Observaciones" auto-grow outlined hide-details
                        rows="1">
                      </v-textarea>
                    </v-col>
                  </v-row>
                  <v-row class="pa-0 ma-0">
                    <v-col class="pa-0 ma-0" outlined tile justify="center" style="font-size: 18px !important;">
                      <v-card class="pa-0 ma-0">
                        <v-card-text :dense="true" style="white-space: pre-line; font-size: 1rem"
                          v-html="textoConSaltosDeLinea()" class="pa-0 ma-0">
                          {{ }}
                        </v-card-text>
                      </v-card>
                    </v-col>
                  </v-row>

                </template>

                <!-- This image from <v-img> will not be visible on print -->

                <!-- This normal <img> would work properly on print -->
              </v-container>

            </v-col>

            <v-col cols="5">
              <v-row v-if="op1 == 1">
                <v-data-table v-if="persona.length > 0" :headers="headers" :footer-props="{
                  itemsPerPageText: 'Pacientes',
                  'items-per-page-options': [15, 30, 50, 100, -1],
                  'items-per-page-all-text': 'Todos'
                }" :items="persona" item-key="ci" :search="search" :header-props='{ sortByText: "Ordenar por" }'
                  @click:row="seleccion_paciente($event)" class="elevation-1">
                  <template v-slot:no-results>
                    <span>No existen datos</span>
                  </template>

                </v-data-table>
                <v-alert v-else :value="true" type="info">
                  No se encontraron datos del Paciente.
                </v-alert>
              </v-row>

              <v-row v-if="op1 == 2">
                <v-col>
                  <v-row>
                    <v-col class="text-center">
                      <v-btn v-if="op1 == 2 && con_cita" color="primary" class="mr-4" @click="dar_cita()">
                        Dar cita
                        <v-icon end icon>mdi-calendar</v-icon>
                      </v-btn>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" class="text-center">
                      Historial de Citas
                    </v-col>
                  </v-row>
                  <v-data-table v-if="citas.length > 0" item-key="fecha" :headers="headers_cita" :footer-props="{
                    itemsPerPageText: 'Citas',
                    'items-per-page-options': [15, 30, 50, 100, -1],
                    'items-per-page-all-text': 'Todas las citas'
                  }" :item-class="getRowClass" :items="citas" :header-props='{ sortByText: "Ordenar por" }'
                    @click:row="" sort-by="fecha" :sort-desc="true" class="elevation-1">
                    <template v-slot:no-results>
                      <span>No existen datos</span>
                    </template>


                    <template v-slot:item.hora_inicio="{ item }">
                      {{ formatoHora(item.hora_inicio) }} - {{ formatoHora(item.hora_final) }}
                    </template>
                    <template v-slot:item.actions="{ item }">
                      <v-btn v-if="fecha_mayor(item.fecha)" small class="mr-2" @click="mostrarDialogo(item)">
                        <v-icon small class="mr-2">mdi-delete</v-icon> Eliminar
                      </v-btn>
                    </template>
                    <template v-slot:item.print="{ item }">

                      <v-btn v-if="fecha_mayor(item.fecha)" small class="mr-2" @click="imprimir_datos(item)">
                        <v-icon>mdi-printer</v-icon> Imprimir
                      </v-btn>
                    </template>
                  </v-data-table>
                  <v-alert v-else :value="true" type="warning">
                    No se encontraron datos de anteriores fichas de usuario.
                  </v-alert>

                  <v-divider></v-divider>
                  <v-row>
                    <v-col cols="12" class="text-center">
                      <span class="headline font-weight-bold"></span>Registro de Fichas medicas
                    </v-col>
                  </v-row>
                  <v-data-table v-if="registro.length > 0" :headers="headers_registro" :footer-props="{
                    itemsPerPageText: 'Registro',
                    'items-per-page-options': [15, 30, 50, 100, -1],
                    'items-per-page-all-text': 'Todas los registros'
                  }" :items="registro" item-key="fecha" :header-props='{ sortByText: "Ordenar por" }'
                    @click:row="show_registro($event)" class="elevation-1">
                    <template v-slot:item.fecha="{ item }">
                      <v-icon>{{ fecha_mayor(item.fecha) ? 'mdi-check' : 'mdi-close' }}</v-icon>
                    </template>
                    <template v-slot:item.actions="{ item }">
                      <v-icon small class="mr-2" @click="eliminarItem(item)">mdi-delete</v-icon>
                    </template>
                    <template v-slot:no-results>
                      <span>No existen datos</span>
                    </template>

                  </v-data-table>
                  <v-alert v-else :value="true" type="success">
                    No se encontraron datos de anteriores citas.
                  </v-alert>
                </v-col>
              </v-row>



            </v-col>
          </v-row>
          <v-row>
            <v-col cols="6" class="text-center">
              <v-btn color="primary" class="m-4" @click="cambiar_datos">
                Guardar Datos
              </v-btn>
            </v-col>
            <v-col cols="6" class="text-center">
              <v-btn v-if="op1 == 2 && con_cita" color="primary" class="mr-4" @click="dar_cita()">
                Dar cita
                <v-icon end icon>mdi-calendar</v-icon>
              </v-btn>
            </v-col>
            <!-- <v-col v-if="op1 == 2" cols="12" sm="4">
                    <v-btn class="ma-2" color="primary">
                      Imprimir
                      <v-icon end icon> mdi-printer</v-icon>
                    </v-btn>
                  </v-col>-->
          </v-row>

          <v-card v-if="buscador == true"> buscar datos </v-card>

        </v-form>
      </v-card>

    </v-card>
    <v-dialog v-model="v_agendar" max-width="600">
      <v-toolbar dark color="#1CA698">
        <v-btn icon dark @click="close_v_agendar()">
          <v-icon>mdi-close</v-icon>
        </v-btn>
        <v-toolbar-title>Agendar Cita</v-toolbar-title>
        <v-spacer></v-spacer>
      </v-toolbar>
      <template>
        <v-card id="section-to-print">
          <v-form ref="form_cita" v-model="validacion">
            <v-container>
              <v-row no-gutters>
                <v-col cols="12" sm="4" md="4">
                  <v-menu v-model="menu2" :close-on-content-click="false" :nudge-right="40" transition="scale-transition"
                    offset-y min-width="auto">
                    <template v-slot:activator="{ on, attrs }">
                      <!--<v-text-field v-model="date" label="Picker without buttons"
                                                                    prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on">
                                                                </v-text-field>-->
                      <v-text-field v-model="cita_nueva.fecha" :rules="nombreRules" placeholder="Selecione fecha de cita"
                        required prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on">
                      </v-text-field>
                    </template>
                    <v-date-picker v-model="cita_nueva.fecha" :allowed-dates="allowedDates" @input="menu2 = false"
                      @change="buscar_consultorios" :min="fechacitaMin" :max="fechacitaMax" locale="es-ES">
                    </v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="12" sm="4" md="4">
                  
                  <v-select v-model="cita_nueva.consultorio" item-text="descripcion" item-value="sala"
                    :items="consultorios" :rules="[validar_seleccion]" persistent-placeholder
                    placeholder="Selecione el Consultorio" color="purple darken-3" @change="buscar_horario"
                    label="Consultorio" required>
                  </v-select>
                </v-col>
                <v-col cols="12" sm="4" md="4">
                  <v-select v-model="cita_nueva.horario" :item-text="(item) => ver_horario(item)" item-value="id_horario"
                    :items="horario" :rules="nombreRules" persistent-placeholder placeholder="Selecione hora de cita"
                    color="purple darken-3" label="Hora de inicio" required>
                  </v-select>
                </v-col>
              </v-row>
              <v-row no-gutters>
                <v-col cols="12" sm="4" md="6">
                  <v-select v-model="cita_nueva.tipo_cita" :items="tipo_cita" color="purple darken-3"
                    persistent-placeholder :rules="nombreRules" placeholder="Selecione tipo de cita" label="Tipo de cita">
                  </v-select>
                </v-col>
                <v-col cols="12" sm="4" md="6">
                  <v-select v-model="cita_nueva.lugar" :items="lugares" color="purple darken-3" :rules="nombreRules"
                    persistent-placeholder placeholder="Selecione lugar de cita" label="Lugar" required>
                  </v-select>
                </v-col>
              </v-row>
              <v-row no-gutters>
                <v-col cols="12" sm="12" md="12">
                  <v-text-field v-model="cita_nueva.observacion" type="text" persistent-placeholder
                    placeholder="Agregar observaciones" label="Observacion">
                  </v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col>

                  <v-btn color="primary" class="mr-4" @click="guardar_cita">
                    Guardar Cita
                  </v-btn>

                </v-col>
                <v-col>
                  <v-btn color="primary" class="mr-4" @click="imprimir_directo" @click.stop="v_agendar = false">
                    Boleta
                  </v-btn>
                </v-col>
              </v-row>

            </v-container>
          </v-form>
        </v-card>
      </template>
    </v-dialog>

    <v-dialog v-model="msm_existe" max-width="600">
      <v-card>
        <v-card-title class="text-h5">
          Usuario existe
        </v-card-title>
        <v-card-text>
          El usuario con
          <p>cedula de identidad: {{ paciente_existen.ci }}</p>
          <p>Nombres:{{ paciente_existen.nombres }}</p>
          <p>Apellido Paterno:{{ paciente_existen.ap_paterno }}</p>
          <p>Apellido Materno:{{ paciente_existen.ap_materno }}</p>


          <p>Identificador:{{ paciente_existen.id_persona }}</p>

          <v-alert v-if="!isEmpty(paciente_existen.fecha_vigencia)" :type="alertType">
            Este paciente está registrado desde el {{ paciente_existen.fecha_registro }} y su fecha de vigencia es hasta
            el {{ paciente_existen.fecha_vigencia }}.
          </v-alert>

          <v-alert v-else>
            No se encontró ningún registro de estas evaluaciones.
          </v-alert>


        </v-card-text>

        <v-card-actions>

          <v-alert type="info" class="text-center mx-auto">
            ¿Deseas utilizar los datos de este usuario?
            <v-spacer></v-spacer>
            <v-btn @click="msm_existe = false">
              Cancelar
            </v-btn>
            <v-btn @click="persona_existente()">
              Usar datos
            </v-btn>
          </v-alert>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="msm_update" max-width="600">
      <v-card>
        <v-card-title class="text-h5">
          La cedula de identidad {{ paciente_edit.ci }}<br />
          fue cambiada por {{ paciente.ci }}
        </v-card-title>
        <v-card-text>
          Desea realizar una nueva busqueda o actulizar del cedula de identidad?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="nueva_busqueda">
            Nueva Busqueda
          </v-btn>
          <v-btn color="green darken-1" text @click="update_ci">
            Actualizar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="msm_update_ci" persistent max-width="300">
      <v-card>
        <v-card-title class="text-h5">
          La cedula de identidad {{ paciente_edit.ci }}<br />
          fue cambiada por {{ paciente.ci }}
        </v-card-title>
        <v-card-text>
          Desea realizar una nueva busqueda o actulizar del cedula de identidad?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="nueva_busqueda">
            Nueva Busqueda
          </v-btn>
          <v-btn color="green darken-1" text @click="update_ci">
            Actualizar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="msm_imprimir" persistent max-width="300">
      <v-card>
        <v-card-title class="text-h5">
          El paciente con cedula de identidad {{ paciente_edit.ci }}<br />
          fue cambiada guradado correctamente {{ paciente.ci }}
        </v-card-title>
        <v-card-text>
          Desea imprimir boleta de cita?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="close_imprimir">
            Cancelar
          </v-btn>
          <v-btn color="green darken-1" text @click="direccion_imprimir">
            imprimir
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogVisible" fullscreen hide-overlay>
      <!-- Contenido del cuadro de diálogo -->
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon @click="cerrar_imprimir()">
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>Boleta de Cita</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <!--<v-btn dark text @click="dialog = false">
                            Save
                        </v-btn>-->
          </v-toolbar-items>
        </v-toolbar>
        <!-- Toolbar -->



        <!-- Contenido del diálogo -->
        <v-card-title>
          <!-- Puedes agregar un título aquí si es necesario -->
        </v-card-title>
        <v-card-text>
          <imprimir v-if="dialogVisible" ref="print">
          </imprimir>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogDeletecita" max-width="400">
      <v-card>
        <v-card-title>Confirmar Eliminación</v-card-title>
        <v-card-text>
          ¿Estás seguro de que deseas eliminar estos datos?
        </v-card-text>
        <v-card-actions>
          <!-- Botón para confirmar la eliminación -->
          <v-btn color="error" @click="eliminarDatos()">Eliminar</v-btn>
          <!-- Botón para cerrar el diálogo -->
          <v-btn @click="cerrarDialogo()">Cancelar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-dialog>
</template>

<script>
//import { thisTypeAnnotation } from "@babel/types";
import axios from "axios";
import moment, { relativeTimeThreshold } from "moment";
import imprimir from '@/Pages/Micomponet/imprimir';
const _ = require('lodash');
const day1 =
  new Date().getFullYear() +
  "-" +
  ("0" + (new Date().getMonth() + 1)).slice(-2) +
  "-" +
  ("0" + new Date().getDate()).slice(-2);

export default {
  components: {

    imprimir
  },
  props: {

    //fichas: Object

  },
  pro(strx) {
    if (strx == "-1") {
      return "No hay";
    }
    return strx;
  },
  data: () => ({
    horarios: [],
    cita: {},
    antiguedadpaciente: '',
    dialogVisible: false,
    dialogDeletecita: false,
    show: false,
    alertType: 'success',
    alertMessage: '',
    con_cita: true,
    options: ["Nuevo", "Recalificado"],
    validacion: false,
    items: [],
    search: '',
    paciente: {},
    paciente_existen: {},
    icon_ci: "mdi-account",
    consultorios: [],
    op: Number,

    v_agendar: false,
    menu2: false,
    datos_informacion: "1",
    consultorio: "",
    sala: [],
    op1: Number,
    valid: false,
    dialog: false,
    buscador: false,
    msm_existe: false,
    msm_update: false,
    msm_update_ci: false,
    msm_imprimir: false,
    ver_apellido_casada: false,
    paciente_edit: {},
    las_citas: [],
    citas: [],
    headers_cita: [
      {
        text: "Fecha",
        align: "start",
        value: "fecha",
      },
      {
        text: "Hora",
        align: "start",
        value: "hora_inicio",
      },
      { text: 'Eliminar', value: 'actions' },

      { text: 'Imprimir', value: 'print' },
    ],
    registro: [],
    headers_registro: [
      {
        text: "Fecha",
        align: "start",
        value: "fecha_registro",
      },

      {
        text: "Fecha de vigencia",
        align: "start",
        value: "fecha_vigencia",
      },

      {
        text: "Tipo de discapacidad",
        align: "start",
        value: "tipo_discapacidad",
      },
      {
        text: "Porcentaje",
        align: "start",
        value: "porcentaje",
      },
      /*{ text: 'Eliminar', value: 'actions' },
      { text: 'Imprimir', value: 'print' },*/

    ],
    show_date: true,
    cita_nueva: {
      fecha: ''
    },
    fecha_cita: "",
    fechacitaMin: "",
    fechacitaMax: "",
    //equipos
    equipos_actuales: [],
    horario: [],
    lista_tiempos: {},
    t_equipo: null,
    sortBy: "fecha",
    sortDesc: true,
    selectRules: [(v) => v != "No se tiene registro" || "Se requiere el dato"],
    tipo_cita: ["RECALIFICADO", "NUEVO"],
    encabezado: [
      {
        text: "Fecha",
        align: "start",
        value: "fecha",
      },
      {
        text: "Consultorio",
        value: "consultorio",
      },
      {
        text: "Paciente",
        value: "ci_paciente",
      },

    ],
    nombreRules: [
      (v) => !!v || "Dato requerido",
      //(v) => (v.length >= 6) || "CI debe de tener mas de 6 caracteres",
      //v => v.length <= 10 || 'CI debe de tener mas de 10 caracteres',
    ],
    ciRules: [
      (v) => !!v || "Dato requerido",
      (v) => (v && v.length >= 4) || "CI debe de tener mas de 6 caracteres",
      //v => v.length <= 10 || 'CI debe de tener mas de 10 caracteres',
    ],
    rules: {
      select: [(v) => !!v || "Item is required"],
      select2: [(v) => validar_seleccion(v)],


    },
    email: "",
    emailRules: [
      (v) => !!v || "E-mail is required",
      (v) => /.+@.+/.test(v) || "E-mail must be valid",
    ],
    rulesApellido: [
      (v) => !!v || "Dato requerido",
      //this.validar_apellido

      //this.validar_apellido()
    ],
    departamentos: [
      "LA PAZ",
      "ORURO",
      "POTOSI",
      "CHUQUISACA",
      "BENI",
      "TARIJA",
      "SANTA CRUZ",
      "COCHABAMBA",
      "PANDO",
    ],
    minFechaNac: "1899-01-01",
    maxFechaNac: day1,
    ssexo: ["MASCULINO", "FEMENINO"],
    errorpaterno: [],
    errormaterno: [],
    itemdelete: {},
    lugares: [],
  }),

  beforeMount() {


  },
  destroyed() {
    //alert('close')
    this.items = []
    this.con_cita = true

  },
  created() {
    //this.paciente_edit = structuredClone(this.paciente);
    //console.log("++++" + moment(this.fechacitaMin).add(1, 'd').format('YYYY-MM-DD')
    //this.buscar_consultorios()
    this.fechacitaMin = moment(this.$store.getters.getfecha_server)
      .add(1, "d")
      .format("YYYY-MM-DD");
    this.fechacitaMax = moment(this.$store.getters.getfecha_server)
      .add(1, "Y")
      .format("YYYY-MM-DD");
    //this.datos_filtrado();
  },
  computed: {
    persona() { return this.items },
    headers() {
      return [
        {
          text: 'Nombres',
          align: 'left',
          //sortable: false,
          value: 'nombres',

          filter: this.nombreFilter,
          //filter: this.nombre_,
          //filter: this.nameFilter,
        },
        {
          text: 'Apellido Paterno',
          value: 'ap_paterno',
          filter: this.paternoFilter,
        },
        {
          text: 'Apellido Materno',
          value: 'ap_materno',
          filter: this.maternoFilter,
          //filter: this.caloriesFilter,
        },
        {
          text: 'Cedula de identidad',
          value: 'ci',
          filter: this.ciFilter,
          //filter: this.esta_en_equipo
          //filter: this.caloriesFilter,
        },
        {
          text: 'Expedido',
          value: 'expedido',
          //filter: this.ciFilter,
          //filter: this.esta_en_equipo
          //filter: this.caloriesFilter,
        },



        /*{
            text: 'Cargo',
            value: 'cargo',
            filter: this.cargoFilter
            //filter: this.caloriesFilter,
        },

        {
            text: 'Equipo',
            value: 'equipo',
            filter: this.esta_en_equipo,
            show: false,
            //filter: this.caloriesFilter,
        },*/


      ]
    }

  },
  watch: {
    //'cita.fecha': 'actualizarCita',  // Observar cambios en cita.fecha
    'cita_nueva.institucion': 'actualizarCita'
    //'cita': 'actualizarCita',  // Observar cambios en cita.fecha
    //'cita.lugar': 'actualizarCita'   // Observar cambios en cita.lugar
  },
  methods: {
    async actualizadorLugar(){
      var res = await axios({
          method: "get",
          url: `/${process.env.MIX_CARPETA}/boleta_datos`,
          data: {
            //: this.paciente_edit,
            //cita: this.cita
          },
        }).then(
          (response) => {

            this.lugares = response.data.lugares
             
          },
          (error) => {
            console.log(error);
          }
        )
    },
    actfecha(){
      this.cita_nueva.fecha = this.fecha_cita 
    },
    async actualizarCita() {
      // Tu lógica aquí
      if (this.isEmpty(this.cita_nueva)) {
        return
      }
      if (this.isEmpty(this.cita_nueva.fecha)) {
        return
      }
      if (!this.isEmpty(this.cita_nueva.institucion)) {
        var res = await axios({
          method: "post",
          url: `/${process.env.MIX_CARPETA}/boleta_datos`,
          data: {
            //: this.paciente_edit,
            cita: this.cita_nueva
          },
        }).then(
          (response) => {
            //console.log("......00000");
            //console.log(this.paciente_edit);
            this.cita.horario = null
            console.log(response.data);
            if(response.data.correcto){
              this.horarios =  response.data.horarios
              this.lugares = response.data.lugares
              //alert(this.cita_nueva.hora_inicio)
              if(!this.isEmpty(this.cita_nueva.id_horario)){
                this.cita.horario = this.cita_nueva.id_horario
  
              }else{
                this.cita.horario = this.horarios[0]
              }
            }else{
              this.cita.horario = undefined
              this.horarios = null
            }
          },
          (error) => {
            console.log(error);
          }
        )
        return
      }

      //alert('cambio')
      console.log('Se ha producido un cambio en fecha o lugar:', this.cita.fecha, this.cita.lugar);
    },
    getlugar(x) {
      return x
      //console.log(this.cita_cita.id_municipio);
      if (this.empty(this.cita_cita.id_municipio)) return this.cita_cita.direccion
      return this.cita_cita.municipio
    },
    textoConSaltosDeLinea() {
      return ''
      //this.boleta.requisitos = structuredClone(this.requisitos)
      return this.requisitos.replace(/\n/g, '<br>');
    },

    fechaTexto(x) {

      moment.locale('es');
      const fecha = moment(x);

      // Obtén la fecha en formato de texto
      return fecha.format('dddd, D [de] MMMM [de] YYYY');
    },
    horaTexto(x) {
      const hora = moment(x, 'HH:mm:ss').format('hh:mm ');

      if (moment(hora, 'hh:mm ').isBefore(moment('12:00', 'hh:mm '))) {
        return `${hora} mañana`;
      } else {
        return `${hora} tarde`;
      }
    },

    mostrarDialogo(item) {
      this.itemdelete = item
      this.dialogDeletecita = true; // Abre el diálogo de confirmación
    },
    cerrarDialogo() {
      this.dialogDeletecita = false; // Cierra el diálogo de confirmación
      delete this.itemdelete
    },
    eliminarDatos() {
      // Aquí puedes agregar la lógica para eliminar los datos
      // Después de eliminar, cierra el diálogo

      this.eliminarItem(this.itemdelete)
    },
    cerrar_imprimir() {
      console.log(this.paciente_edit)
      console.log(this.paciente_existen)
      this.$store.dispatch('guardar_imprimir', null);
      localStorage.removeItem("usuario");
      this.dialogVisible = false
    },
    imprimir_datos(item) {
      // Abrir la pestaña principal
      //this.$store.commit('update_imprimir', this.selectedEvent.fichas);
      //console.log(this.selectedEvent.fichas);
      //this.$store.commit('update_imprimir', this.selectedEvent.fichas)
      //console.log('/*/*/*/*/*/*/')
      //console.log(this.selectedEvent.fichas)
      this.$store.dispatch('guardar_imprimir', item);
      console.log('fsadf');
      console.log(item)
      localStorage.setItem("usuario", JSON.stringify(item));
      setTimeout(() => {

      }, 2);
      this.dialogVisible = true
    },
    isEmpty(value) {
      return value === undefined || value === null || value === '' || (typeof value === 'object' && Object.keys(value).length === 0);
    },
    showAlert(type) {
      this.show = true;
      this.alertType = type;

      // Personaliza el mensaje según el tipo de alerta
      switch (type) {
        case 'success':
          this.alertMessage = '¡Éxito! Esta es una alerta de éxito.';
          break;
        case 'info':
          this.alertMessage = 'Información: Esto es solo para informarte.';
          break;
        case 'warning':
          this.alertMessage = 'Advertencia: Algo podría no estar bien.';
          break;
        case 'error':
          this.alertMessage = '¡Error! Algo salió mal.';
          break;
        default:
          this.alertMessage = '';
      }
    },
    validar_apellido(v) {



      if (!_.isEmpty(this.paciente.ap_paterno) || !_.isEmpty(this.paciente.ap_materno)) {

        this.errorpaterno = []
        this.errormaterno = []
        return true
      }
      console.log('______')
      this.errorpaterno = 'Este campo es requerido'
      this.errormaterno = 'Este campo es requerido'

      return false


    },

    seleccion_paciente(value) {
      this.paciente_existen = value
      this.msm_existe = true
    },
    ciFilter(value) {
      //console.log(this.paciente.nombres);
      if (typeof this.paciente.ci == 'undefined') return true
      if (this.paciente.ci == '') return true
      if (value.includes(this.paciente.ci)) return true;
      return false;
    },

    nombreFilter(value) {
      //console.log(this.paciente.nombres);
      if (typeof this.paciente.nombres == 'undefined') return true
      if (this.paciente.nombres == '') return true
      if (value.includes(this.paciente.nombres)) return true;
      return false;
    },
    paternoFilter(value) {
      //console.log(this.paciente.ap_paterno);
      if (typeof this.paciente.ap_paterno == 'undefined') return true
      if (this.paciente.ap_paterno == '') return true
      if (value.includes(this.paciente.ap_paterno)) return true;
      return false;
    },
    maternoFilter(value) {
      console.log(this.paciente.ap_materno);
      if (typeof this.paciente.ap_materno == 'undefined') return true
      if (this.paciente.ap_materno == '') return true
      if (value.includes(this.paciente.ap_materno)) return true;
      return false;
    },
    /*  inicialiazar fecha minima*/
    async datos_filtrado() {
      if (this.op1 == 1) {
        var res = await axios({
          method: 'get',
          url: `/${process.env.MIX_CARPETA}/persona`,
          /*data: {
            ficha: this.selectedEvent.fichas,
            equipo: this.selectequipo.equipo
          }*/
        }).then(
          (response) => {
            console.log("---_:.-.-.-.-.-.-.::::::::----");
            console.log(response);
            this.items = response.data
            /*this.selectedEvent.fichas = structuredClone(response.data);
            //this.selectedEvent.color = 'yellow'
            this.pedir_datos()
            this.selectedOpen = false
            */
          }
        ).catch(err => {
          console.log(err)

        });
      }


    },
    validar_seleccion() {
      /*console.log("___________");
      console.log(this.consultorios);
      
      console.log(this.cita_nueva.consultorio);
      console.log(this.consultorios.filter(e => e.sala === this.cita_nueva.consultorio).length>0);*/
      if (this.consultorios.filter(e => e.sala === this.cita_nueva.consultorio).length > 0) {
        return true;
      } else {
        //this.consultorio = null
        //this.cita_nueva.consultorio =null
        return 'valor requerido'
      }
    },
    direccion_imprimir() {
      console.log(".....");
      console.log(this.horario)
      console.log(this.cita_nueva)

      for (const K in this.horario) {
        if (this.horario[K].id_horario == this.cita_nueva.horario) {
          console.log(this.horario[K].hora_inicio + " - " + this.horario[K].hora_final);
          this.cita_nueva.hora_inicio = this.horario[K].hora_inicio + " - " + this.horario[K].hora_final

        }
      }
      let cita = Object.assign(this.paciente, this.cita_nueva)
      localStorage.setItem('cita', JSON.stringify(cita));
      window.open(`/${process.env.MIX_CARPETA}/imprimir`);
      this.close_imprimir()
    },
    open_imprimir() {
      this.msm_imprimir = true

    },
    close_imprimir() {
      this.msm_imprimir = false

    },
    fecha_min() {
      console.log("-----");
      console.log(this.$store.getters.getfecha_server);
      let fecha = moment(this.$store.getters.getfecha_server)
        .add(1, "d")
        .format("YYYY-MM-DD");
      return fecha;
    },
    fecha_max() {
      let fecha = moment(this.$store.getters.getfecha_server)
        .add(6, "M")
        .format("YYYY-MM-DD");
      console.log("hohooh");
      console.log(fecha);

      return fecha;
    },
    openAgendar() {
      //this.fecha_cita;
      this.v_agendar = true;
      this.cita_nueva.fecha = this.fecha_cita;
      this.cita_nueva.consultorio = this.consultorio;
      this.buscar_consultorios();
      console.log(this.cita_nueva);
      /*var res = await axios({
                method: 'get',
                url: `/${process.env.MIX_CARPETA}/api/buscar_persona/` + this.paciente.ci,
            }).then(
                (response) => {
                    console.log(response);
                    if (response['data']['mensaje'] == 'SQLSTATE[23505]:') {
                        //let rep = response['data']['persona']
                        this.msm_existe = true
                        this.paciente_existen = response['data']['persona']
                    }
                }, (error) => {
                    console.log(error);
                }*/
    },
    nueva_busqueda() {
      this.op1 = 1;
      let datos = structuredClone(this.paciente.ci);
      this.paciente = {};
      this.paciente_edit = {};
      this.paciente_existen = {};
      this.cita = {};
      this.paciente.ci = datos;
      this.msm_update = false;
      this.msm_update_ci = false;

      this.buscadorporci();
      this.$refs.formDatopersonales.resetValidation();
    },
    update_ci() {
      this.msm_update_ci = false;
      this.cambiar_datos();
    },
    ver_horario(item) {
      return (
        moment(item.hora_inicio, "hh:mm:ss").format("HH:mm") +
        " - " +
        moment(item.hora_final, "hh:mm:ss").format("HH:mm")
      );
    },
    async buscar_horario() {
      console.log(this.cita_nueva);

      try {
        var res = await axios({
          method: "post",
          url: `/${process.env.MIX_CARPETA}/api/horario_disponible`,
          data: {
            cita_nueva: this.cita_nueva,
          },
        }).then(
          (response) => {
            console.log(response);
            this.horario = response.data;
            this.horario.sort()
            this.horario[0];
            //this.horario =  response.data['horario']
          },
          (error) => {
            console.log(error);
          }
        );
      } catch (err) {
        console.log("err->", err.response.data);
        return res
          .status(500)
          .send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
      }
    },

    async buscadorporci() {
      if (this.paciente.ci == "") {
        return;
      }
      if (this.op1 == 1) {
        console.log(this.paciente.ci);
        var res = await axios({
          method: "get",
          url:
            `/${process.env.MIX_CARPETA}/api/buscar_persona/` +
            this.paciente.ci,
        }).then(
          (response) => {
            console.log(response);
            if (response["data"]["mensaje"] == "SQLSTATE[23505]:") {
              //let rep = response['data']['persona']
              this.msm_existe = true;
              this.paciente_existen = response["data"]["persona"];
            }
          },
          (error) => {
            console.log(error);
          }
        );
      } else {
        this.paciente_existen = {};
      }
      if (this.op1 == 2) {
        this.msm_update = true;
      }
    },
    async buscadorporvalor(valor) {
      console.log('--------');
      console.log(this.paciente);
      if (this.op1 == 1) {
        console.log(this.paciente.ci);
        var res = await axios({
          method: "post",
          url:
            `/${process.env.MIX_CARPETA}/api/buscar_valor`,
          data: {
            paciente: this.paciente
          },
        }).then(
          (response) => {
            console.log(response);
            this.items = response.data
            /*if (response["data"]["mensaje"] == "SQLSTATE[23505]:") {
              //let rep = response['data']['persona']
              this.msm_existe = true;
              this.paciente_existen = response["data"]["persona"];
            }*/
          },).catch((error) => {
            //console.log(error.response.data.mensaje);

          });
      }
      if (this.op1 == 2) {
        //this.msm_update = true
      }
    },

    async eliminarItem(item) {
      console.log(item);
      axios.delete(`/${process.env.MIX_CARPETA}/dar_ficha/` + item.id_ficha)
        .then(response => {
          // Manejar la respuesta del servidor en caso de éxito
          console.log(response.data);
          this.pedir_citas()
          this.cerrarDialogo()
        })
        .catch(error => {
          // Manejar el error en caso de que la petición falle
          console.log(error);
        });

      // Lógica para eliminar el elemento de la lista de items
      //const index = this.items.indexOf(item);
      //this.items.splice(index, 1);
    },

    fecha_mayor(x) {
      //console.log(x, " ", this.$store.getters.getfecha_server);
      const fecha1 = new Date(x);
      const fecha2 = new Date(this.$store.getters.getfecha_server);

      if (fecha2 <= fecha1) {
        return true;
      } else {
        return false;
      }

    },
    imprimir(item) {
      console.log(item
      );
      this.$store.dispatch('guardar_imprimir', item);
      localStorage.setItem("usuario", JSON.stringify(item));
      setTimeout(() => {

      }, 2);

      let mainTab = window.open(`/${process.env.MIX_CARPETA}/imprimir`, '_blank');
    },
    async pedir_citas() {

      console.log(this.paciente_edit);
      var res = await axios({
        method: "post",
        url: `/${process.env.MIX_CARPETA}/api/get_citas`,
        data: {
          paciente: this.paciente_edit,
          cita: this.cita
        },
      }).then(
        (response) => {
          console.log("......00000");
          console.log(this.paciente_edit);
          console.log(response.data);
          this.con_cita = true
          this.citas = response['data']['cita']
          this.registro = response['data']['registro']
          if (this.registro.length === 0) {
            this.antiguedadpaciente = 'Nuevo'
          } else {
            this.antiguedadpaciente = 'Recalificado'
          }
          this.paciente = this.paciente_edit
          //this.horario =  response.data['horario']
        },
        (error) => {
          console.log(error);
        }
      )
    },

    getRowClass(item) {
      const fecha1 = new Date(item.fecha);
      const fecha2 = new Date(this.$store.getters.getfecha_server);

      console.log('+-+-+-+-+-+-+-+');
      console.log(fecha1 + '<=' + fecha2);
      // Comparar las fechas
      if (fecha2 <= fecha1) {
        this.con_cita = false;
        return 'yellow lighten-3';
      } else {
        if (item.id_designado === null) {
          return 'blue lighten-3';
        } else if (item.id_designado) {
          return 'green lighten-3';
        }
      }

    },

    persona_existente() {

      console.log("fffffff");


      this.paciente = this.paciente_existen;
      this.paciente_existen = {};
      this.msm_existe = false;
      this.paciente_edit = structuredClone(this.paciente);
      this.op1 = 2;
      this.pedir_citas()

    },
    alert(text) {
      this.$alert(text).then((res) => this.$inform("Cambios guardados!"));
    },
    pro(strx) {
      if (strx == "-1") {
        return "No hay";
      }
      return strx;
    },
    forceUpper(value) {
      value = value.toUpperCase();
    },
    open() {
      this.dialog = true;
      //this.datos_filtrado()
    },
    async tabselect(a) {
      this.datos_informacion = a;
    },
    buscadoractivate() { },
    close(event) {
      console.log(this.cita_nueva.fecha);
      console.log("----");
      this.$emit("pedir", this.fecha_cita);
      this.items = []
      this.dialog = false;
      this.cita_nueva = {};
      this.paciente = {};
      this.cita = {}
      this.datos_informacion = "";
      this.antiguedadpaciente = "";
    },
    close_v_agendar() {
      this.v_agendar = false;
    },
    async cambiar_datos() {
      var a = this.validar_apellido()
      if (this.$refs.formDatopersonales.validate() && a) {
        console.log(this.paciente);
        console.log(" antiguo: ");
        console.log(this.paciente_edit);
        /*var res = await axios({
          method: "post",
          url: `/${process.env.MIX_CARPETA}/configuracion`,
          data: {
            nuevo: this.paciente,
            antiguo: this.paciente_edit,
            opcion: this.op1,
          },
        }).then(
          (response) => {
            console.log(response);
            ////console.log('validat');
            ////console.log(response);
            //console.log('__configuracion ___');
            //console.log(response.data);
            
            this.activar(response)



          },
        ).catch((error) => {
          //console.log(error.response.data.mensaje);

        });*/
        this.guardar_datos()
      }
    },
    mostrarDatos(x) {
      console.log('----------------');
      console.log(x);
      this.paciente_edit = structuredClone(x)
      
      this.pedir_citas()
      this.actualizarCita()
      //this.pedir_datos()
    },
    async guardar_datos() {
      var res = await axios({
        method: "post",
        url: "api/guardar_persona",
        data: {
          nuevo: this.paciente,
          antiguo: this.paciente_edit,
          opcion: this.op1,
        },
      }).then(
        (response) => {
          var res = response
          /*console.log(response);
          this.pedir_datos()
          this.selectedOpen = false*/
          console.log(res);
          if (res["data"]["mensaje"] == "ok") {
            console.log("inserccion correcta");
            this.alert("Inserccion Correcta");
            this.paciente = res["data"]["persona"];
            this.paciente_edit = structuredClone(this.paciente);
            this.op1 = 2;
            this.pedir_citas()
            return;
          }
          if (res["data"]["mensaje"] == "ok update") {
            console.log("update correcto");
            this.alert("Actulizacion Correcta");
            this.paciente = res["data"]["persona"];
            this.paciente_edit = structuredClone(this.paciente);
            this.op1 = 2;
            return;
          }
          if (res["data"]["mensaje"] == "SQLSTATE[23505]:" && this.op1 == 1) {
            this.msm_existe = true;
            this.paciente_existen = structuredClone(res["data"]["persona"]);
            return;
            //this.paciente_edit = structuredClone(this.paciente)
            //this.paciente = res['data']['persona']
          }
          if (res["data"]["mensaje"] == "SQLSTATE[23505]:" && this.op1 == 2) {
            this.alert(
              "No se puede cambiar la cedula de identidad " +
              this.paciente_edit.ci +
              " por " +
              this.paciente.ci +
              ". Por que esta (" +
              this.paciente_edit.ci +
              ") ya existe. Se volvera a la antigua configuracion"
            );
            this.paciente = structuredClone(this.paciente_edit);
            this.paciente_existen = {};
            this.op1 = 2;


          }
        }).catch(err => {
          console.log(err)
          console.log("err->", err.response.data)
          return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
        });
      //this.paciente_edi t = structuredClone(this.paciente)
      //this.paciente = res['data']['persona']
    },
    async buscar_citas() {
      /*console.log('......');
      console.log(this.paciente);
      try {
        var res = await axios({
          method: "get",
          url:
            `/${process.env.MIX_CARPETA}/api/buscar_persona_citas/` +
            this.paciente.id,
        }).then(
          (response) => {
            console.log(response);
            this.las_citas = response.data;
          },
          (error) => {
            console.log(error);
          }
        );
      } catch (error) { }*/
    },
    async valorar() { },
    allowedDates(val) {
      return true;
    },
    async buscar_consultorios() {
      try {
        var res = await axios({
          method: "post",
          url: `/${process.env.MIX_CARPETA}/api/buscar_consultorios`,
          data: {
            datos_agenda: this.cita_nueva,
          },
        }).then(
          (response) => {
            console.log("......");
            console.log(response);
            this.consultorios = response.data;
            this.buscar_horario();
            //this.horario =  response.data['horario']
          },
          (error) => {
            console.log(error);
          }
        );
      } catch (err) {
        console.log("err->", err.response.data);
        return res
          .status(500)
          .send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
      }
    },
    async guardar_cita() {
      if (this.$refs.form_cita.validate()) {
        console.log("--------")
        console.log(this.cita_nueva.horario)
        let hof = moment(

          this.cita_nueva.fecha + "T" + this.cita_nueva.hora_inicio
        ).add(1, "h");

        this.cita_nueva.ci_paciente = this.paciente.ci;
        var res = await axios({

          method: "post",
          url: `/${process.env.MIX_CARPETA}/agendar`,
          data: {
            cita: this.cita_nueva,
            paciente: this.paciente,
          },
        }).then(
          (response) => {
            console.log(response);
            this.v_agendar = false;
            this.buscar_citas();
            this.open_imprimir()


          }
        ).catch((error) => {
          console.log(error.response)
          this.alert("occurio un error")
          return;
        });


        /*console.log(res['data']);
                this.las_citas = res['data'];
                this.v_agendar = false;*/
      }
    },
    imprimir_directo() {
      this.selectedEvent = {
        fecha: this.fecha_cita_actual,
        hora: this.t_equipo,
        equipo: this.equipo_actual,
        tipo_cita: this.ttipocita,
        se_presento: "",
        observacion: this.tobservacion,
        lugar: this.tlugar,
        ci: this.ci,
        name: this.nombre,
        ap_paterno: this.paterno,
        ap_materno: this.materno,
      };
      console.log(this.tobservacion);

      this.v_imprimir = true;
    },
    activar(response) {
      this.close()
      console.log(response);
      let res = response
      if (res["data"]["mensaje"] == "ok") {
        console.log("inserccion correcta");
        this.alert("Inserccion Correcta");
        this.paciente = res["data"]["persona"];
        this.paciente_edit = structuredClone(this.paciente);
        this.op1 = 2;
        return;
      }
      if (res["data"]["mensaje"] == "ok update") {
        console.log("update correcto");
        this.alert("Actulizacion Correcta");
        this.paciente = res["data"]["persona"];
        this.paciente_edit = structuredClone(this.paciente);
        this.op1 = 2;
        return;
      }
      if (res["data"]["mensaje"] == "SQLSTATE[23505]:" && this.op1 == 1) {
        this.msm_existe = true;
        this.paciente_existen = structuredClone(res["data"]["persona"]);
        return;
        //this.paciente_edit = structuredClone(this.paciente)
        //this.paciente = res['data']['persona']
      }
      if (res["data"]["mensaje"] == "SQLSTATE[23505]:" && this.op1 == 2) {
        this.alert(
          "No se puede cambiar la cedula de identidad " +
          this.paciente_edit.ci +
          " por " +
          this.paciente.ci +
          ". Por que esta (" +
          this.paciente_edit.ci +
          ") ya existe. Se volvera a la antigua configuracion"
        );
        this.paciente = structuredClone(this.paciente_edit);

        this.paciente_existen = {};
        this.op1 = 2;
      }
    },
    onEnter(event) {
      // evitar que la tecla Enter haga algo dentro del diálogo
      event.preventDefault();
    },
    formatoHora(x) {
      return moment(x, "HH:mm:ss").format("HH:mm A")
    },
    async dar_cita() {
      console.log(this.cita_nueva);
      
      var res = await axios({
        method: "post",
        url: `/${process.env.MIX_CARPETA}/dar_ficha`,
        data: {
          cita: this.cita_nueva,
          paciente: this.paciente,
          fecha: this.fecha_cita
        },
      }).then(
        (response) => {
          console.log(response);
          this.pedir_citas()
        }
      ).catch((error) => {
        console.log(error.response)
        this.alert("occurio un error")
        return;
      });
    }

  },
};
</script>
