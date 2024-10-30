// Create New Menu Item
document
  .getElementById("itemForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    let formData = new FormData(this);

    fetch("items.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        alert(data);

        let modal = bootstrap.Modal.getInstance(
          document.querySelector("#staticBackdropLabel").closest(".modal")
        );
        modal.hide();

        document.getElementById("itemForm").reset();

        window.location.reload();
      })
      .catch((error) => console.error("Error:", error));
  });

// Turn off disabled Save button if any changes are made
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("editForm");
  const saveButton = document.getElementById("saveButton");
  const initialFormState = new FormData(form);

  form.addEventListener("input", function () {
    const currentFormState = new FormData(form);
    let isChanged = false;

    for (let [key, value] of currentFormState.entries()) {
      if (value !== initialFormState.get(key)) {
        isChanged = true;
        break;
      }
    }

    saveButton.disabled = !isChanged;
  });
});

// Clear form after Cancel
document.getElementById("cancelButton").addEventListener("click", function () {
  document.getElementById("itemForm").reset();
});

document.addEventListener("DOMContentLoaded", function () {
  const deleteButtons = document.querySelectorAll(".delete-button");
  const itemToDelete = document.getElementById("itemToDelete");
  const deleteItemName = document.getElementById("deleteItemName");

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const itemName = this.getAttribute("data-item-name");
      itemToDelete.textContent = itemName;
      deleteItemName.value = itemName;
    });
  });
});

// Handle delete form submission
document.getElementById("deleteForm").addEventListener("submit", function (event) {
  event.preventDefault();

  let formData = new FormData(this);

  fetch("items.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((data) => {
      alert(data);

      let modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
      modal.hide();

      window.location.reload();
    })
    .catch((error) => console.error("Error:", error));
});

// Handle update
document.addEventListener("DOMContentLoaded", function () {
  const editModal = document.getElementById("editModal");
  let initialData = {};

  editModal.addEventListener("show.bs.modal", function (event) {
    const button = event.relatedTarget;
    const itemId = button.getAttribute("data-id");


    initialData = {
      itemId,
      itemName: button.getAttribute("data-name"),
      itemPrice: button.getAttribute("data-price"),
      itemDescription: button.getAttribute("data-description"),
      imageUrl: button.getAttribute("data-image"),
      category: button.getAttribute("data-category"),
      outOfStock: button.getAttribute("data-stock") === "1"
    };
    console.log(initialData);

    editModal.querySelector('input[name="item_name"]').value = initialData.itemName;
    editModal.querySelector('input[name="item_Price"]').value = initialData.itemPrice;
    editModal.querySelector('textarea[name="item_description"]').value = initialData.itemDescription;
    editModal.querySelector(".modal-body img").src = initialData.imageUrl;
    editModal.querySelector('select[name="category_name"]').value = initialData.category;
    editModal.querySelector("#flexSwitchCheckDefault").checked = initialData.outOfStock;
    editModal.querySelector(".modal-title").textContent = `Edit Menu Item: ${initialData.itemName}`;
    editModal.querySelector('input[name="itemId"]').value = initialData.itemId;
  });

  document.getElementById("editForm").addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData();

    const fields = [
      { name: 'item_name', value: editModal.querySelector('input[name="item_name"]').value },
      { name: 'item_Price', value: editModal.querySelector('input[name="item_Price"]').value },
      { name: 'item_description', value: editModal.querySelector('textarea[name="item_description"]').value },
      { name: 'category_name', value: editModal.querySelector('select[name="category_name"]').value },
      { name: 'outOfStock', value: editModal.querySelector("#flexSwitchCheckDefault").checked ? "1" : "0" },
      { name: 'image', value: editModal.querySelector('input[type="file"]').files[0] }
    ];

    fields.forEach(field => {
      if (field.value !== initialData[field.name]) {
        formData.append(field.name, field.value);
      }
    });

    formData.append("itemId", initialData.itemId);
    formData.append("action", "update");

    fetch("items.php", { method: "POST", body: formData })
      .then(response => response.text())
      .then(data => {
        alert(data);
        bootstrap.Modal.getInstance(editModal).hide();
        location.reload();
      })
      .catch(error => console.error("Error:", error));
  });
});
