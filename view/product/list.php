<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="home">
        <form id="addProductForm" method="POST">
            <input type="hidden" id="productId" value="">
            <input type="text" name="name" id="name" placeholder="Product Name" required>
            <input type="number" name="price" id="price" placeholder="Price" required>
            <button type="submit" id="submitButton">Add Product</button>
            <button type="button" id="saveButton" style="display:none;">Save Changes</button>
        </form>

        <table id="productTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="productList"></tbody>
        </table>

        <form id="updateProductForm" style="display:none;" method="POST">
            <input type="hidden" id="updateProductId">
            <input type="text" id="updateProductName" placeholder="Product Name" required>
            <input type="number" id="updateProductPrice" placeholder="Price" required>
            <button type="submit">Update Product</button>
        </form>
    </div>

<script>
    $(document).ready(function() {
        
        function fetchUsers() {
            $.ajax({
                url: 'http://localhost/aht-training/lession12plus//?act=list',
                method: 'GET',
                success: function(responseJson) {
                    if (typeof responseJson === 'string') {
                        responseJson = JSON.parse(responseJson);
                    }

                    let products = responseJson;
                    let productList = '';
                    
                    products.forEach(product => {
                        productList += `
                            <tr data-id="${product.id}">
                                <td>${product.id}</td>
                                <td>${product.name}</td>
                                <td>${product.price} Đ</td>
                                <td>
                                    <button class="updateBT" data-id="${product.id}">Update</button>
                                    <button class="deleteBT" data-id="${product.id}">Delete</button>
                                </td>
                            </tr>
                        `;
                    });

                    $('#productList').html(productList);

                    $('.deleteBT').on('click', function() {
                        const productId = $(this).data('id');
                        deleteProduct(productId);
                    });

                    $('.updateBT').on('click', function() {
                        const productId = $(this).data('id');
                        loadProductForUpdate(productId);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error in AJAX request:', textStatus, errorThrown);
                    if (jqXHR.responseText) {
                        let response = JSON.parse(jqXHR.responseText);
                        $('#productList').text(response.message);
                    }
                }
            });
        }

        function deleteProduct(id) {
            $.ajax({
                url: `http://localhost/aht-training/lession12plus//?act=delete&id=${id}`,
                method: 'GET',
                success: function(responseJson) {
                    $(`tr[data-id="${id}"]`).remove();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error deleting product:', textStatus, errorThrown);
                    if (jqXHR.responseText) {
                        let response = JSON.parse(jqXHR.responseText);
                        alert(response.message || 'Error occurred while deleting the product');
                    }
                }
            });
        }

        function loadProductForUpdate(id) {
            $.ajax({
                url: `http://localhost/aht-training/lession12plus/?act=edit&id=${id}`,
                method: 'GET',
                success: function(responseJson) {
                    if (typeof responseJson === 'string') {
                        responseJson = JSON.parse(responseJson);
                    }

                    const product = responseJson;
                    if (product && product.id && product.name && product.price) {
                        $('#updateProductId').val(product.id);
                        $('#updateProductName').val(product.name);
                        $('#updateProductPrice').val(product.price);

                        $('#updateProductForm').show();
                        $('#addProductForm').hide();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error loading product data:', textStatus, errorThrown);
                }
            });
        }

        $('#updateProductForm').on('submit', function(event) {
            event.preventDefault();

            const id = $('#updateProductId').val();
            const name = $('#updateProductName').val();
            const price = $('#updateProductPrice').val();

            $.ajax({
                url: `http://localhost/aht-training/lession12plus/?act=save`,
                method: 'POST',
                data: { id, name, price },
                success: function(responseJson) {
                    fetchUsers();
                    resetForm();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error updating product:', textStatus, errorThrown);
                    if (jqXHR.responseText) {
                        let response = JSON.parse(jqXHR.responseText);
                        alert(response.message || 'Error occurred while updating the product');
                    }
                }
            });
        });

        $('#addProductForm').on('submit', function(event) {
            event.preventDefault();

            const name = $('#name').val();
            const price = $('#price').val();

            if (name && price) {
                $.ajax({
                    url: 'http://localhost/aht-training/lession12plus//?act=add',
                    method: 'POST',
                    data: {
                        name: name,
                        price: price
                    },
                    success: function(responseJson) {
                        fetchUsers();
                        $('#name').val('');
                        $('#price').val('');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error adding product:', textStatus, errorThrown);
                        if (jqXHR.responseText) {
                            let response = JSON.parse(jqXHR.responseText);
                            alert(response.message || 'Error occurred while adding the product');
                        }
                    }
                });
            }
        });

        function resetForm() {
            $('#updateProductForm').hide();
            $('#addProductForm').show();
            $('#updateProductId').val('');
            $('#updateProductName').val('');
            $('#updateProductPrice').val('');
        }

        fetchUsers();
    });
</script>
</body>
</html>
