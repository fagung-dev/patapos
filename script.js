function showDetails(item) {
  const qtyElement = document.getElementById(`qty-${item.id}`);
  let qty = parseInt(qtyElement.value);
  tambahKeKeranjang(item.id, item.product_name, item.price, qty);
  qtyElement.value = 1;
}

function tambahKeKeranjang(itemId, itemName, itemPrice, qty) {
  const keranjangList = document.getElementById("keranjang-list");
  const existingItem = Array.from(keranjangList.children).find(
    (li) => li.dataset.itemId === itemId
  );

  const totalCurrencies = document.getElementById("totalBelanjaCurrencies");
  const total = document.getElementById("totalBelanja");
  let currentTotal = parseInt(total.value || 0);

  if (existingItem) {
    const existingQty = parseInt(existingItem.dataset.itemQty);
    const newQty = existingQty + qty;
    existingItem.dataset.itemQty = newQty;
    existingItem.innerHTML = `
            <div class="d-flex justify-content-between">
                <div>
                    <strong>${newQty} - ${itemName}</strong> - ${formatCurrency(
      itemPrice * newQty
    )}
                </div>
                <div>
                    <span class="text-danger" style="cursor: pointer;" onclick="hapus('${itemId}', ${itemPrice})">Hapus</span>
                </div>
            </div>
            <hr />
        `;
  } else {
    const newItem = document.createElement("li");
    newItem.dataset.itemId = itemId;
    newItem.dataset.itemName = itemName;
    newItem.dataset.itemQty = qty;
    newItem.innerHTML = `
            <div class="d-flex justify-content-between">
                <div>
                    <strong>${qty} - ${itemName}</strong> - ${formatCurrency(
      itemPrice * qty
    )}
                </div>
                <div>
                    <span class="text-danger" style="cursor: pointer;" onclick="hapus('${itemId}', ${itemPrice})">Hapus</span>
                </div>
            </div>
            <hr />
        `;

    keranjangList.appendChild(newItem);
  }

  const finalTotal = currentTotal + itemPrice * qty;
  total.value = finalTotal;
  totalCurrencies.innerHTML = `Rp. ${formatCurrency(finalTotal)}`;
  setStatusButton(false);
}

function formatCurrency(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function kurangiQty(itemId) {
  const qtyElement = document.getElementById(`qty-${itemId}`);
  let qty = parseInt(qtyElement.value);

  if (qty > 1) {
    qty--;
    qtyElement.value = qty;
  }
}

function tambahQty(itemId) {
  const qtyElement = document.getElementById(`qty-${itemId}`);
  let qty = parseInt(qtyElement.value);

  qty++;
  qtyElement.value = qty;
}

function hapus(itemId, itemPrice) {
  const keranjangList = document.getElementById("keranjang-list");
  const totalCurrencies = document.getElementById("totalBelanjaCurrencies");
  const total = document.getElementById("totalBelanja");

  const itemToRemove = Array.from(keranjangList.children).find(
    (li) => li.dataset.itemId === itemId
  );

  if (itemToRemove) {
    const reduction = itemPrice * parseInt(itemToRemove.dataset.itemQty);
    const currentTotal = parseInt(total.value || 0);
    const finalTotal = currentTotal - reduction;
    total.value = finalTotal;
    totalCurrencies.innerHTML = `Rp. ${formatCurrency(finalTotal)}`;

    keranjangList.removeChild(itemToRemove);
  }

  const items = Array.from(keranjangList.children);
  if (items.length === 0) {
    setStatusButton(true);
  }
}

function setStatusButton(disabled) {
  const button = document.getElementById("buttonOrder");
  if (disabled) {
    button.disabled = true;
  } else {
    button.disabled = false;
  }
}

function createOrder() {
  const keranjangList = document.getElementById("keranjang-list");
  const items = Array.from(keranjangList.children);

  const payload = [];
  for (const item of items) {
    payload.push({
      itemId: item.dataset.itemId,
      itemName: item.dataset.itemName,
      itemQty: item.dataset.itemQty,
    });
  }

  Swal.fire({
    title: "Orderan berhasil dibuat.",
    icon: "success",
    confirmButtonColor: "#3085d6",
  }).then(() => hapusSemuaKeranjang());
}

function hapusSemuaKeranjang() {
  setStatusButton(true);
  const keranjangList = document.getElementById("keranjang-list");
  const totalCurrencies = document.getElementById("totalBelanjaCurrencies");
  const total = document.getElementById("totalBelanja");

  while (keranjangList.firstChild) {
    keranjangList.removeChild(keranjangList.firstChild);
  }

  total.value = 0;
  totalCurrencies.innerHTML = `Rp. ${formatCurrency(0)}`;
}

function editProduct(productId) {
  window.location.href = "edit_produk_view.php?id=" + productId;
}

function deleteProduct(productId) {
  Swal.fire({
    title: "Apakah kamu yakin akan menghapus produk ini?",
    showCancelButton: true,
    confirmButtonText: "Save",
    denyButtonText: `Don't save`,
    confirmButtonColor: "#3085d6",
  }).then((result) => {
    if (result.isConfirmed) {
      request = $.ajax({
        url: "delete_product_proccess.php",
        type: "post",
        data: { id: productId },
      });

      request.done(function (response, textStatus, jqXHR){
        window.location.href = "produk.php?success=Sukses menghapus produk"
    });
    }
  });
}
