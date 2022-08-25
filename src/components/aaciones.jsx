import React from 'react';
import Delete from "./delete.jsx";
import Modificar from "./modificar.jsx";

const Boton = ({ texto,  manejarClic,id }) => {  
 switch (manejarClic) {
   case "Delete":
     return <button onClick={() => Delete(id)}>{texto}</button>;
     break;
   case "Modificar":
     return <button onClick={() => Modificar(id)}>{texto}</button>;
     break;
 }
}
export default Boton;