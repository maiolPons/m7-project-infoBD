function confirmacioD(curs){
    let isExecuted = confirm("Estas segura que et vols desmatricular del curs " + curs +" ?");
    if(isExecuted){
        document.cookie='desmatricular='+curs;
    }
    
}
function confirmacioM(curs){
    let isExecuted = confirm("Estas segura que et vols smatricular en el curs " + curs +" ?");
    if(isExecuted){
        document.cookie='smatricular='+curs;
    }
    
}