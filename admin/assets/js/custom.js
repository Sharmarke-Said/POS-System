$(document).ready(function () {
  alertify.set("notifier", "position", "top-right");

  // alertify.success("test");

  $(document).on("click", ".increment", function () {
    var $quantityInput = $(this).closest(".qtyBox").find(".qty");
    var productId = $(this).closest(".qtyBox").find(".prodID").val();

    var currentValue = parseInt($quantityInput.val());

    if (!isNaN(currentValue)) {
      var qtyVal = currentValue + 1;
      $quantityInput.val(qtyVal);
      quantityIncDec(productId, qtyVal);
    }
  });

  $(document).on("click", ".decrement", function () {
    var $quantityInput = $(this).closest(".qtyBox").find(".qty");
    var productId = $(this).closest(".qtyBox").find(".prodID").val();

    var currentValue = parseInt($quantityInput.val());

    if (!isNaN(currentValue) && currentValue > 1) {
      var qtyVal = currentValue - 1;
      $quantityInput.val(qtyVal);
      quantityIncDec(productId, qtyVal);
    }
  });

  function quantityIncDec(prodID, qty) {
    $.ajax({
      type: "post",
      url: "orders-code.php",
      data: {
        producttIncDec: true,
        product_id: prodID,
        quantity: qty,
      },
      success: function (response) {
        var res = JSON.parse(response);

        if (res.status == 200) {
          // window.location.reload();
          $("#productArea").load(" #productContent");
          alertify.success(res.message);
        } else {
          alertify.error(res.message);
        }
      },
    });
  }

  // proceed to place order
  $(document).on("click", ".proceedToPlace", function () {
    var payment_mode = $("#payment_mode").val();
    var cphone = $("#cphone").val();

    if (payment_mode == "" && !$.isNumeric(cphone)) {
      swal("Select Payment Mode", "Select your payment mode", "warning");
      return false;
    }
    if (cphone == "") {
      swal("Enter Phone Number", "Enter valid phone number", "warning");
      return false;
    }

    var data = {
      proceedToPlaceBtn: true,
      cphone: cphone,
      payment_mode: payment_mode,
    };

    $.ajax({
      type: "post",
      url: "orders-code.php",
      data: data,
      success: function (response) {
        var res = JSON.parse(response);
        if (res.status == 200) {
          window.location.href = "order-summary.php";
        } else if (res.status == 404) {
          swal(res.message, res.message, res.status_type, {
            buttons: {
              catch: {
                text: "Add Customer",
                value: "catch",
              },
              cancel: "cancel",
            },
          }).then((value) => {
            switch (value) {
              case "catch":
                // console.log("Pop up the add customer modal");
                $("#c_phone").val(cphone);
                $("#addCustomerModal").modal("show");
                break;

              default:
                break;
            }
          });
        } else {
          swal(res.message, res.message, res.status);
        }
      },
    });
  });

  // Add customer to the Customers DB table

  $(document).on("click", ".saveCustomer", function () {
    var c_name = $("#c_name").val();
    var c_phone = $("#c_phone").val();
    var c_email = $("#c_email").val();

    if (c_name != "" && c_phone != "") {
      if ($.isNumeric(c_phone)) {
        var data = {
          saveCustomerBtn: true,
          c_name: c_name,
          c_phone: c_phone,
          c_email: c_email,
        };
        $.ajax({
          type: "post",
          url: "orders-code.php",
          data: data,
          success: function (response) {
            var res = JSON.parse(response);
            if (res.status == 200) {
              swal(res.message, res.message, res.status_type);
              $("#addCustomerModal").modal("hide");
            } else if (res.status == 422) {
              swal(res.message, res.message, res.status_type);
            } else {
              swal(res.message, res.message, res.status_type);
            }
          },
        });
      } else {
        swal("Enter a valid phone number", "", "warning");
      }
    } else {
      swal("Please fill in the required fields", "", "warning");
    }
  });

  $(document).on("click", "#saveOrder", function () {
    $.ajax({
      type: "POST",
      url: "orders-code.php",
      data: {
        saveOrderBtn: true,
      },
      success: function (response) {
        var res = JSON.parse(response);
        if (res.status == 200) {
          swal(res.message, res.message, res.status_type);
          $("#orderSuccessMessage").text(res.message);
          $("#orderSuccessModal").modal("show");
        } else {
          swal(res.message, res.message, res.status_type);
        }
      },
    });
  });
});

// function printMyBillingArea(){
//     var divContents = document.getElementById("myBillingArea").innerHTML;
//     var a = window.open('', '');
//     a.document.write('<><title>POS System In PHP</title>');
//     a.document.write('<body style="font-family: fangsong;" >');
//     a.document.write(divContents);
//     a.document.write('</body></html>');
//     a.document.close();
//     a.print();
// }

function printMyBillingArea() {
  var myBillingArea = document.getElementById("myBillingArea");
  var clone = myBillingArea.cloneNode(true);

  var printWindow = window.open("", "_blank");
  printWindow.document.write(
    '<html><head><title>POS System In PHP</title></head><body style="font-family: fangsong;">'
  );
  printWindow.document.body.appendChild(clone);
  printWindow.document.write("</body></html>");
  printWindow.document.close();

  printWindow.print();
}



// Dowload PDF
window.jsPDF = window.jspdf.jsPDF;
var docPDF = new jsPDF();
function downloadPDF(invoiceNo) {
  var elementHTML = document.querySelector("#myBillingArea");
  docPDF.html(elementHTML, {
    callback: function () {
      docPDF.save(invoiceNo + ".pdf");
    },
    x: 15,
    y: 15,
    width: 170,
    windowWidth: 650,
  });
}
