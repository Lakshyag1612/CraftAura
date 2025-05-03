<?php
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Add Product | CraftAura</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen pt-24">

  <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-xl p-8">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">➕ Add a New Product</h2>

    <form id="productForm" enctype="multipart/form-data" class="space-y-6">
      <div>
        <label class="block mb-1 text-sm font-semibold text-gray-700">Product Name</label>
        <input type="text" name="name" required placeholder="e.g. Handmade Vase" class="input-field">
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block mb-1 text-sm font-semibold text-gray-700">Price (Rs)</label>
          <input type="number" step="0.01" name="price" required placeholder="e.g. 599" class="input-field">
        </div>
        <div>
          <label class="block mb-1 text-sm font-semibold text-gray-700">Category</label>
          <select name="category" required class="input-field">
            <option value="">Select Category</option>
            <option value="Pottery">Pottery</option>
            <option value="Statues">Statues</option>
            <option value="Painting">Painting</option>
            <option value="Home Decor">Home Decor</option>
          </select>
        </div>
      </div>

      <div>
        <label class="block mb-1 text-sm font-semibold text-gray-700">Description</label>
        <textarea name="description" required placeholder="Describe the product..." rows="4" class="input-field"></textarea>
      </div>

      <div>
        <label class="block mb-1 text-sm font-semibold text-gray-700">Product Image</label>
        <input type="file" name="image" accept="image/*" required class="input-field">
      </div>

      <div class="flex items-center space-x-3">
        <input type="checkbox" id="special_offer" name="special_offer" class="w-4 h-4 text-yellow-500 focus:ring-yellow-400 border-gray-300 rounded">
        <label for="special_offer" class="text-sm text-gray-700">Mark as Special Offer</label>
      </div>

      <button type="submit" class="w-full bg-yellow-500 text-white font-semibold py-2 rounded hover:bg-yellow-400 transition duration-200">
        Add Product
      </button>
    </form>
  </div>

  <!-- Modal -->
  <div id="modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full text-center">
      <p id="modalText" class="text-lg text-gray-700"></p>
      <button onclick="closeModal()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">OK</button>
    </div>
  </div>

  <script>
    const form = document.getElementById('productForm');
    const modal = document.getElementById('modal');
    const modalText = document.getElementById('modalText');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const formData = new FormData(form);
      formData.append('special_offer', document.getElementById('special_offer').checked ? 1 : 0);

      try {
        const res = await fetch('upload_product.php', {
          method: 'POST',
          body: formData
        });

        const data = await res.json();
        modalText.textContent = data.message || 'Something went wrong.';
        modal.classList.remove('hidden');

        if (data.status === 'success') form.reset();
      } catch (err) {
        modalText.textContent = 'Failed to submit. Please try again.';
        modal.classList.remove('hidden');
      }
    });

    function closeModal() {
      modal.classList.add('hidden');
    }
  </script>

  <style>
    .input-field {
      width: 100%;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 10px 14px;
      outline: none;
      transition: border 0.3s, box-shadow 0.3s;
    }

    .input-field:focus {
      border-color: #facc15;
      box-shadow: 0 0 0 3px rgba(250, 204, 21, 0.4);
    }
  </style>

</body>

</html>
