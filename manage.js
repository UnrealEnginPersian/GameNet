const form = document.getElementById("systemForm");
const listDiv = document.getElementById("systemList");

function loadSystems() {
  fetch("php/system.php?action=list")
    .then(res => res.json())
    .then(data => {
      listDiv.innerHTML = "";
      data.forEach(system => {
        let div = document.createElement("div");
        div.className = "system";

        div.innerHTML = `
          <b>${system.name}</b> | سرویس: ${system.last_service_date} | هزینه: ${system.cost_per_second} <br>
          <button onclick="deleteSystem(${system.id})">❌ حذف</button>
          <button onclick="editSystem(${system.id}, '${system.name}', '${system.last_service_date}', ${system.cost_per_second})">✏️ ویرایش</button>
        `;
        listDiv.appendChild(div);
      });
    });
}

function deleteSystem(id) {
  if (confirm("آیا مطمئن هستید؟")) {
    fetch("php/system.php?action=delete&id=" + id)
      .then(() => loadSystems());
  }
}

function editSystem(id, name, date, cost) {
  document.getElementById("name").value = name;
  document.getElementById("last_service_date").value = date;
  document.getElementById("cost_per_second").value = cost;

  form.onsubmit = (e) => {
    e.preventDefault();
    const data = {
      name: nameInput.value,
      last_service_date: dateInput.value,
      cost_per_second: costInput.value
    };

    fetch("php/system.php?action=update&id=" + id, {
      method: "POST",
      body: JSON.stringify(data)
    }).then(() => {
      form.reset();
      loadSystems();
      form.onsubmit = submitHandler;
    });
  };
}

const nameInput = document.getElementById("name");
const dateInput = document.getElementById("last_service_date");
const costInput = document.getElementById("cost_per_second");

function submitHandler(e) {
  e.preventDefault();
  const data = {
    name: nameInput.value,
    last_service_date: dateInput.value,
    cost_per_second: costInput.value
  };

  fetch("php/system.php?action=add", {
    method: "POST",
    body: JSON.stringify(data)
  }).then(() => {
    form.reset();
    loadSystems();
  });
}

form.onsubmit = submitHandler;

loadSystems();
