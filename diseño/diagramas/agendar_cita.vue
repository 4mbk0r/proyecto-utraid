@startuml
actor Usuario
boundary DatosPersonales
control ApiREST
database BaseDatos
alt#Gold #LightBlue caso Ingreso Datos Cedula De Identidad (existo)
Usuario -> DatosPersonales: Ingresa numero de carnet
activate DatosPersonales


DatosPersonales --> ApiREST: Busca carnet\n url: "/api/buscar_persona/{ci}"\n methods: GET 
Activate ApiREST

ApiREST -> BaseDatos: Consulta: Buscar persona por CI\n Table: personas
Activate BaseDatos
BaseDatos --> ApiREST: Resultado: Buscar persona por CI\n
deactivate BaseDatos
ApiREST --> DatosPersonales: Devuelve mensaje de no encontrado
DatosPersonales --> Usuario: Muestra el mensaje de no encontrado

else #Pink Usuario existente

ApiREST --> DatosPersonales: Devuelve los datos encontrado
deactivate ApiREST

DatosPersonales --> Usuario: Muestra el mensaje de Usuario ya existe
'destroy ApiREST

deactivate DatosPersonales
end

'-------------------------------'
alt#Gold #LightBlue caso Ingreso de Datos Paciete Nombres, Apellido Paterno  y Materno  (existo)
alt#Gold #LightBlue caso Validacion de Datos Paciete Nombres, Apellido Paterno  y Materno  (existo)

Usuario -> DatosPersonales: Ingresa Datos
activate DatosPersonales

DatosPersonales->DatosPersonales : Validar_datos: segun las reglas
'DatosPersonales->Usuario: mensaje correcto
else #Pink  si falla la validacion de datos
DatosPersonales->Usuario: mensaje datos incorrectos o faltantes


end
DatosPersonales --> ApiREST: Busca dato\n url: "/api/buscar_valor"\n methods: POST 
Activate ApiREST

ApiREST -> BaseDatos: Consulta: Buscar nombre, apellidos \n Table: personas
Activate BaseDatos
BaseDatos --> ApiREST: Resultado: Buscar nombre, apellidos \n
deactivate BaseDatos
ApiREST --> DatosPersonales: Devuelve Colleccion de Datos encontrado
DatosPersonales --> Usuario: Despliega Lista de Paciente encontrados
deactivate ApiREST

/'
else #Pink  si el usuario ya existe

ApiREST --> DatosPersonales: Devuelve los datos encontrado
deactivate ApiREST

DatosPersonales --> Usuario: Despliega Lista de Paciente encontrados
'destroy ApiREST
'/
deactivate DatosPersonales
end
'-------------------'
alt#Gold #LightBlue caso Registrar datos del Paciente (existo)
alt#Gold #LightBlue caso Validacion de Datos Paciete\n CI, expedido, Nombres, Apellido Paterno  y Materno  (existo)

Usuario -> DatosPersonales: Guardar datos
activate DatosPersonales

DatosPersonales->DatosPersonales : Validar_datos: Se debe de insertar minimamente \n CI, expedido, Nombres, Apellido Paterno  y Materno 
'DatosPersonales->Usuario: mensaje correcto
else #Pink  si falla datos de validacion 
DatosPersonales->Usuario: mensaje datos incorrectos o faltantes
end
DatosPersonales --> ApiREST: Validar Datos Registro\n url: "api/guardar_persona"\n methods: POST 
Activate ApiREST
ApiREST -> BaseDatos: Consulta: Insertar Datos a Registro\n Table: personas
Activate BaseDatos
BaseDatos --> ApiREST: Devuelver datos inserccion  \n
deactivate BaseDatos
ApiREST --> DatosPersonales: Devuelve Datos de Inserccion
DatosPersonales --> Usuario: Despliega Datos del Paciente
'deactivate ApiREST


else #Pink  Si los datos del registro no son validos

ApiREST --> DatosPersonales: Devuelve Mensaje de Error de Validacion
deactivate ApiREST

DatosPersonales --> Usuario: Muestra Mensaje de Error
'destroy ApiREST

deactivate DatosPersonales
end

@enduml
