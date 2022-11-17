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
function posarNota(dni,curs){
    var isExecuted=false;
    let nota = prompt("Escriu la nota: ");
    while(nota>10 || nota<0){
        alert("La nota a de ser entre 0 i 10");
        nota = prompt("Escriu la nota: ");
    }
    if(nota<10 || nota>0){
        isExecuted=true;
    }
    if(isExecuted){
        document.cookie='cursnota='+curs;
        document.cookie='dninota='+dni;
        document.cookie='notanota='+nota;
        location.reload();
    }
}