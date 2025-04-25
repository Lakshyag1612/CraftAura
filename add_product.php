
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="bg-white shadow-xl rounded-lg p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Add a New Product</h2>

    <form id="productForm" enctype="multipart/form-data" class="space-y-5">
      <input type="text" name="name" required placeholder="Product Name" class="input-field">
      <input type="number" step="0.01" name="price" required placeholder="Price (€)" class="input-field">
      <input type="text" name="category" placeholder="Category" class="input-field">
      <textarea name="description" required placeholder="Product Description" class="input-field" rows="4"></textarea>

      <input type="file" name="image" accept="image/*" required class="input-field">
      <button type="submit" class="w-full bg-yellow-500 text-white py-2 rounded hover:bg-yellow-400 transition">Add Product</button>
    </form>
  </div>

  <!-- Modal -->
  <div id="modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded shadow-lg max-w-sm w-full text-center">
      <p id="modalText" class="text-lg text-gray-700"></p>
      <button onclick="closeModal()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">OK</button>
    </div>
  </div>

  <script>
    const form = document.getElementById('productForm');
    const modal = document.getElementById('modal');
    const modalText = document.getElementById('modalText');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const formData = new FormData(form);

      const res = await fetch('upload_product.php', {
        method: 'POST',
        body: formData
      });

      const data = await res.json();
      modalText.textContent = data.message;
      modal.classList.remove('hidden');

      if (data.status === 'success') form.reset();
    });

    function closeModal() {
      modal.classList.add('hidden');
    }
  </script>

  <style>
    .input-field {
      width: 100%;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 10px;
      outline: none;
      transition: border 0.3s;
    }
    .input-field:focus {
      border-color: #facc15;
      box-shadow: 0 0 0 2px rgba(250, 204, 21, 0.3);
    }
  </style>

</body>
</html>
