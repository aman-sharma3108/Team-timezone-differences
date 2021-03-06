"use strict"




function init(){
    var dropdowns = document.getElementById("dropbtn");
    dropdowns.onclick = function (){
      document.getElementById("myDropdown").classList.toggle("show");
    };
    
    var TimePeriodDropdown = document.getElementById("time_period");
    if (TimePeriodDropdown != null) {
        TimePeriodDropdown.onchange = function () {
            var timeperiod = this.value;
            document.getElementById("monthly").setAttribute("hidden","hidden");
            document.getElementById("weekly").setAttribute("hidden", "hidden");
            if (timeperiod=="monthly") {
                document.getElementById("monthly").removeAttribute("hidden");
            }
            if (timeperiod=="weekly") {
                document.getElementById("weekly").removeAttribute("hidden");
            }
        };
    }
    
    if (document.getElementById("new_sale") != null){
        var InputsWrapper = $("#input_wrapper");
        var AddButton = document.getElementById("AddProduct");
        
        
        AddButton.onclick = function (){
            var ProductID = document.getElementById("product_dropdown").value;
            var ProductName = $('#product_dropdown option:selected').text();
            var ProductQuantity = document.getElementById("quantity").value;
            var ProductSubTotal = document.getElementById("subtotal").value;
            $(InputsWrapper).append('<p class="row"><label for="productname">Product: </label><input type="text" placeholder="Product ID" class="products" name="products[]" value="'+ProductID+'" readonly> <input type="text" placeholder="Product Name" value="'+ProductName+'" readonly> x <input type="text" placeholder="Quantity" class="quantities" name="quantities[]" value="'+ProductQuantity+'" readonly/> = <input type="text" placeholder="Subtotal" class="subtotals" name="subtotals[]" value="'+ProductSubTotal+'" readonly/> <button type="button" class="removeclass">x</button></p>');
            calculateTotal();
            //reset quantity
            document.getElementById("quantity").value = null;
            calculateSubTotal();
            return false;
        };

        
        $("body").on("click",".removeclass", function(e){
            $(this).parent('p').remove();
            calculateTotal();
        return false;
        });

        var aestTime = new Date().toLocaleString("en-US", {timeZone: "Australia/Melbourne"});
        aestTime = new Date(aestTime);
        var year = aestTime.getFullYear();
        var month = aestTime.getMonth() + 1;
        var day = aestTime.getDate();
        if (month < 10){
          month = "0" + month;
        }
        if (day < 10){
          day = "0" + day;
        }
        document.getElementById('dos').value = year + "-" +month + "-" +day +"T"+ aestTime.getHours() + ":" +aestTime.getMinutes();
        
    }
}

function getPrice() {
    var ProductDropDownValue = document.getElementById("product_dropdown").value;
    document.getElementById("price_dropdown").value = ProductDropDownValue;
    calculateSubTotal();
    return false;
}

function calculateSubTotal() {
    var Price = parseFloat($('#price_dropdown option:selected').text());
    var Quantity = document.getElementById("quantity").value;
    var SubTotal = Price * Quantity;
    document.getElementById("subtotal").value = SubTotal;
}

function calculateTotal(){
    var total = 0;
    $('.subtotals').each(function(){
        total = total+parseFloat($(this).val());
    });
    console.log(total);
    document.getElementById("tprice").value=total;
}
window.onload = init;
