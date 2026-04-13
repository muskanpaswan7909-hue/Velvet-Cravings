document.getElementById("orderForm").addEventListener("submit",function(e){

e.preventDefault()

let inputs=document.querySelectorAll("input")

let name=inputs[0].value
let phone=inputs[1].value
let cake=inputs[2].value
let address=inputs[3].value

let msg="New Cake Order%0A"+
"Name:"+name+"%0A"+
"Phone:"+phone+"%0A"+
"Cake:"+cake+"%0A"+
"Address:"+address

let url="https://wa.me/7879280127?text="+msg

window.open(url)

})