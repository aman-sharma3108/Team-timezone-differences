"use strict"




function init(){
    if (document.getElementById("new_sale") != null){
        var MaxInputs = 10;
        var InputsWrapper = $("#input_wrapper");
        var AddButton = document.getElementById("AddMoreFileBox");
        var x = InputsWrapper.length;
        
        
        AddButton.onclick = function (){
            if(x < MaxInputs)
            {
                $(InputsWrapper).append('<p class="row"><label for="productname">Product: </label><input type="text" placeholder="Product ID" class="products" name="products[]"> x <input type="text" placeholder="Quantity" class="quantities" name="quantities[]" maxlength="4" size="4"/> = <input type="text" placeholder="Subtotal" class="subtotals" onchange="calc()" name="subtotals[]" size="6"/> <button type="button" class="removeclass">x</button></p>');
                x++;
            }
        return false;
        };

        
        $("body").on("click",".removeclass", function(e){
            if(x > 1){
                $(this).parent('p').remove();
                x--;
            }
            calc();
        return false;
        });

        
    }
    
    var dropdowns = document.getElementById("dropbtn");
    dropdowns.onclick = function (){
      document.getElementById("myDropdown").classList.toggle("show");
    };
}

function calc(){
    var InputsWrapper = $("#input_wrapper");
    var x = InputsWrapper.length;
    var total = 0;
    
    $('.subtotal').each(function(){
        total = total+parseFloat($(this).val());
        
    });
    document.getElementById("tprice").value=total;
}
window.onload = init;
