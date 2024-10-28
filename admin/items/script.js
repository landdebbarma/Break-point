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

// Fetch data from db and populate Edit modal
document.addEventListener("DOMContentLoaded", function () {
  const editModal = document.getElementById("editModal");
  editModal.addEventListener("show.bs.modal", function (event) {
    const button = event.relatedTarget;

    const itemName = button.getAttribute("data-name");
    const itemDescription = button.getAttribute("data-description");
    const imageUrl = button.getAttribute("data-image");
    const category = button.getAttribute("data-category");
    const inStock = button.getAttribute("data-stock") !== "1";

    editModal.querySelector('input[name="item_name"]').value = itemName;
    editModal.querySelector('textarea[name="item_description"]').value =
      itemDescription;
    editModal.querySelector(".modal-body img").src = imageUrl;
    editModal.querySelector('select[name="category_name"]').value = category;
    editModal.querySelector("#flexSwitchCheckDefault").checked = inStock;
    editModal.querySelector(".modal-title").textContent =
      "Edit Menu Item: " + itemName;
  });
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

// Capture the item name for deletion
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
document.getElementById("deleteForm").addEventListener("submit", function(event) {
  event.preventDefault();

  let formData = new FormData(this);

  fetch("items.php", {
      method: "POST",
      body: formData,
  })
  .then((response) => response.text())
  .then((data) => {
      alert(data); // Display the response message as an alert

      // Hide the delete modal
      let modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
      modal.hide();

      window.location.reload();
  })
  .catch((error) => console.error("Error:", error));
});
