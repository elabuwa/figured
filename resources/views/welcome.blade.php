<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
              integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    </head>
    <body>
        <div class="container mt-5">
                <div class="row">
                    <h3>Check Inventory Levels</h3> 
                    <form id="quantity_check_form">
                        <div class="form-group">
                            <label for="email">Quantity:</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="0">
                        </div>
                        <button type="submit" class="mt-1 btn btn-primary">Submit</button>
                    </form>
                </div>
        </div>
    </body>
<script>
    async function postData(url = '', data = {}) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        return response.json();
    }

    let form = document.getElementById("quantity_check_form");
    form.addEventListener("submit", function(event) {
        event.preventDefault();
        postData('{{ route('checkQuantity') }}', { quantity: document.getElementById('quantity').value })
            .then((data) => {
                if(data.hasOwnProperty('isAvailable') && data.isAvailable === false){
                    alert(`Required quantity is not availale. Max available is ${data.totalAvailable}`);
                } else {
                    alert(`Cost of stock in hand is ${data.cost}`);
                }
                console.log(data);
            });
    });

</script>
</html>
