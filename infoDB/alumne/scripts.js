function confirmacioD(curs){
    let isExecuted = confirm("Estas segura que et vols desmatricular del curs " + curs +" ?");
    if(isExecuted){
        document.cookie='desmatricular='+curs;
        location.reload();
    }
    
}
function confirmacioM(curs){
    let isExecuted = confirm("Estas segura que et vols matricular en el curs " + curs +" ?");
    if(isExecuted){
        document.cookie='matricular='+curs;
        location.reload();
        
    }
    
}