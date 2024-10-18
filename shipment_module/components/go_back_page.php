<style>
    .pickup-cover-back-page{
        background-color: rgba(0, 0, 0, 0.6);
        width: 100%;
        height: 100%;
        transition: 0.3s ease-in-out;
        min-height: 70vh;
        display: block;
    }

    .back-page-container{
        background-color: #FFFFFF;
        border-radius: 1rem;
        padding: 30px 30px;
        text-align: center;
    }

    .back-page-container button{
        background-color: #C62525;
        border-radius: 1rem;
        padding: 15px 30px;
        text-align: center;
        color: #FFFFFF;
        width:70%;
        margin: 10px;
        font-size: 22px;
        font-weight: 700;
    }

</style>

<div class="black-cover-back-page">
    <div class="back-page-container">
        <h2>[404] PAGE IS NOT FOUND!</h2>
        <button id="goBackPageBtn">BACK</button>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 
<script>
    $(document).ready(function(){
        $('button#goBackPageBtn').click(function() {
            history.back()
        });
    });
</script>