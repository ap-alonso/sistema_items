import React from "react";
function Delete(id02) {
 console.log(`Delete Id: , ${id02}`);
  const apiUrl = "http://localhost/serv_local/sistema_items/api/items/delete";
  const requestOptions = {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ 'id': id02 }),
    mode: "no-cors",
  };
  fetch(apiUrl, requestOptions)
    .then((data) => console.log(data))

  return <p>Items Registrados</p>;
}
export default Delete;



