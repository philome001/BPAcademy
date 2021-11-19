<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>BPA Karibu</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="{{asset('css/app.css')}}">

     
    </head>
    <body>
    <div class="container">
   
    <div class="row mt-5">
            <div class="col-sm-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                       Request Access Token
                    </div>
                  
                    <div class="card-body">
                        <h4 id="access_token"></h4>
                        <button id="getAccessToken" class="btn btn-primary">Request Access Token</button>
                       
                    </div>
                </div>

                <div class="card mt-5">
                    <div class="card-header">Register URLs</div>
                    <div class="card-body">
                        <div id="response"></div>
                        <button id="registerURLS" class="btn btn-primary">Register URLs</button>
                    </div>
                </div>

                <div class="card mt-5">
                    <div class="card-header">Simulate Transaction</div>
                    <div class="card-body">
                        <div id="c2b_response"></div>
                        <form action="">
                            @csrf
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" class="form-control" id="amount">
                            </div>
                            <div class="form-group">
                                <label for="account">Account</label>
                                <input type="text" name="account" class="form-control" id="account">
                            </div>
                            <button id="simulate" class="btn btn-primary">Simulate Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
   <script src="{{asset('js/app.js')}}"></script>
   <script>
   document.getElementById('getAccessToken').addEventListener('click', (event) => {
    if(event){
        event.preventDefault()
   
    axios.post('/get-token', {})
    .then((response) => {
         console.log(response);
       
        document.getElementById('access_token').innerHTML=response.data
        }) 
    .catch((error) => {
        console.log(error);
    })
    }else{console.log('No event')}
    })
    //register URLs
    document.getElementById('registerURLS').addEventListener('click', (event) => {
    event.preventDefault()

    axios.post('register-urls', {})
    .then((response) => {
        if(response.data.ResponseDescription){
            document.getElementById('response').innerHTML = response.data.ResponseDescription
        } else {
            document.getElementById('response').innerHTML = response.data.errorMessage
        }
        console.log(response.data);
    })
    .catch((error) => {
        console.log(error);
    })
  document.getElementById('simulate').addEventListener('click', (event) => {
    event.preventDefault()
    const requestBody = {
        amount: document.getElementById('amount').value,
        account: document.getElementById('account').value
    }
    axios.post('/simulate', requestBody)
    .then((response) => {
        if(response.data.ResponseDescription){
            document.getElementById('c2b_response').innerHTML = response.data.ResponseDescription
        } else {
            document.getElementById('c2b_response').innerHTML = response.data.errorMessage
        }
        console.log(response.data);
    })
    .catch((error) => {
        console.log(error);
    })
})

});

  
   </script>
</body>
</html>
