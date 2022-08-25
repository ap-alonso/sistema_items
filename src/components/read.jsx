import { useState, useEffect } from "react";
import Boton from "./aaciones.jsx";

function Read() {
  const [articulos, setArticulos] = useState([]);
  useEffect(() => {
    fetch("http://localhost/serv_local/sistema_items/api/items/read")
      .then((response) => {
        return response.json();
      })
      .then((articulos) => {
        setArticulos(articulos.items);
      });
  }, []);
  return (
    <div>
      <table border="1">
        <thead>
          <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          {articulos.map((art) => {
            return (
              <tr key={art.id}>
                <td>{art.id}</td>
                <td>{art.name}</td>
                <td>{art.description}</td>
                <td>
                  <Boton texto="Borrar" manejarClic="Delete" id={art.id} />
                  <Boton
                    texto="Modificar"
                    manejarClic="Modificar"
                    id={art.id}
                  />
                </td>
              </tr>
            );
          })}
        </tbody>
      </table>
    </div>
  );
}
export default Read;
