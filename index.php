<button id="link-button">Link Account</button>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>
<script type="text/javascript">
    (async function() {
        async function fetchLinkToken() {
            let response = await fetch('../paymentSample/create_link_token.php');
            let responseJSON  = await response.json();
            return responseJSON.link_token;
        }

        const configs = {
            token: await fetchLinkToken(),
            env: 'sandbox',
            onSuccess: async function(public_token, metadata){
                account_id = metadata.accounts[0].id
                $.post( '../paymentSample/process_plaid_token.php', {public_token,account_id}, function( data ) {                        
                    console.log('data : '+data);
                    if (data=='Success'){              
                        console.log('Success');
                        // window.location.replace('thankyou.php');
                    }else{
                        console.log('Error');
                        // window.location.replace('error.php');
                    }
                });
            },
            onExit: async function(err, metadata) {
                if (err != null) {
                    console.log(err);
                    console.log(metadata);   
                }
            }
        }

        var linkHandler = Plaid.create(configs);

        document.getElementById('link-button').onclick = function() {
            linkHandler.open();
        };
    })();
</script>