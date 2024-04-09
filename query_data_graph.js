let currentChart;

function detruireGraphiqueActuel() {
    if (currentChart) {
        currentChart.destroy();
        currentChart = null;
    }
}





function couleurAleatoire() {
    return `rgba(${[...Array(3)].map(() => Math.floor(Math.random() * 255)).join(',')},0.5)`;
}

document.addEventListener('DOMContentLoaded', function() {
var ctx = document.getElementById('graphs').getContext('2d');

function updateBarGraph(selectedYear) {
var filteredData = datasets.filter(dataset => dataset.label === selectedYear);

 detruireGraphiqueActuel();
filteredData.forEach(dataset => {
dataset.backgroundColor = dataset.data.map((_, index) => {
    return `hsl(${index * 50 % 360}, 100%, 70%)`;
});
});

var ctx = document.getElementById('graphs').getContext('2d');
currentChart = new Chart(ctx, {
type: 'bar',
data: {
    labels: categories,
    datasets: filteredData
},
options: {
    scales: {
        y: {
            beginAtZero: true,
            title :{
                display : true,
                text : "Nombre de prix Nobel"
               
            }
        },
        x: {
            title :{
                display : true,
                text :"Catégories de prix nobel"
            }
        }
    },
    plugins : {
        title:{
            display: true,
            text : "Répartition des prix nobels par catégorie en fonction de l'année"
        },
        legend:{
            display : false
        }
    }
}
});
}



// function grapheLineaire() {
// var categories = [...new Set(donneesHistorique.map(item => item.Nom_catégorie))];
// var intervalles = [...new Set(donneesHistorique.map(item => item.Intervalle))].sort();

// var datasets = categories.map(catégorie => {
// var dataPourCategorie = donneesHistorique.filter(item => item.Nom_catégorie === catégorie);
// var data = intervalles.map(intervalle => {
// var item = dataPourCategorie.find(item => item.Intervalle === intervalle);
// return item ? item.nbLauréats : 0;
// });

// return {
// label: catégorie,
// data: data,
// fill: false,

// };
// });
// detruireGraphiqueActuel();

// var ctx = document.getElementById('graphs').getContext('2d');
// currentChart = new Chart(ctx, {
// type: 'line', 
// data: {
// labels: intervalles,
// datasets: datasets
// },
// options: {
// scales: {
//     y: {
//         beginAtZero: true
//     }
// },
// plugins: {
//     title: {
//         display: true,
//         text: 'Nombre de lauréats par catégorie et intervalle d\'années'
//     }
// }
// }
// });

// }



/// genereration des historiques sous formes de plusieurs graphes 
function genererGraphique(typeGraphique, datasets) {
    detruireGraphiqueActuel();
    var ctx = document.getElementById('graphs').getContext('2d');
    currentChart = new Chart(ctx, {
        type: typeGraphique,
        data: {
            labels: datasets.labels,
            datasets: datasets.datasets
        },
        options: datasets.options
    });
}

/// genereration des historiques sous formes de plusieurs graphes 

function genererGraphiqueBarres(labels, donnees, couleurs, titre, pasEchelle) {
    detruireGraphiqueActuel();
    var ctx = document.getElementById('graphs').getContext('2d');
    currentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                backgroundColor: couleurs,
                data: donnees,
            }]
        },
        options: {
            indexAxis: 'y',
            plugins: {
                title: {
                    display: true,
                    text: titre
                },
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: pasEchelle
                    }
                }
            }
        }
    });
}

//le graphe circulaire sur le sexe par categories et annnée 
function updatePieGraph(year) {
    const totalByGender = { male: 0, female: 0 };
    categories.forEach(category => {
    if (maleData[year] && maleData[year][category]) {
        totalByGender.male += maleData[year][category];
    }
    if (femaleData[year] && femaleData[year][category]) {
        totalByGender.female += femaleData[year][category];
    }
    });
    
    var total = totalByGender.male + totalByGender.female;
    detruireGraphiqueActuel();
    var ctx = document.getElementById('graphs').getContext('2d');
    currentChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Hommes', 'Femmes'],
        datasets: [{
            label: `Nombre de Prix Nobel par Sexe en ${year}`,
            data: [totalByGender.male, totalByGender.female],
            backgroundColor: ['#42A5F5', '#EC407A'],
            borderColor: ['darkblue', 'darkred'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            datalabels: {
                color: '#FFF',
                formatter: (value, ctx) => {
                    let sum = ctx.dataset._meta[0].total;
                    let percentage = (value * 100 / sum).toFixed(2) + "%";
                    return percentage;
                },
                anchor: 'end',
                align: 'start',
                offset: -10
            },
            legend: {
                display: true,
                position: 'top',
            },
            title: {
                display: true,
                text: `Distribution des Prix Nobel par Sexe en ${year}`
            }
        }
    }
    });
}

function grapheEnCambert(selectYear) {
        var yearData = datasets.find(dataset => dataset.label === selectedYear);
        if (!yearData) return; 
        var ctx = document.getElementById('graphs').getContext('2d');
        
        detruireGraphiqueActuel();
        var total = yearData.data.reduce((acc, value) => acc + Number(value), 0);
        currentChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: categories,
            datasets: [{
                label: `Distribution des prix Nobel en ${selectedYear}`,
                data: yearData.data,
                backgroundColor: categories.map((_, index) => `hsl(${index * 360 / categories.length}, 70%, 50%)`),
                hoverOffset: 4
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: true,
                    text: `Distribution des Prix Nobel par Catégorie en ${selectedYear}`
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            var value = context.raw;
                            var percentage = ((value / total) * 100).toFixed(2) + "%";
                            return `${label}: ${value} (${percentage})`;
                        }
                    }
                }
            },
            responsive: true,
        }
        });
}

function grapheEnCam2(){
    detruireGraphiqueActuel();
    var ctx = document.getElementById('graphs').getContext('2d');
    currentChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: queryCam.map(item => item.Gender),
        datasets: [{
            label: `Distribution des prix Nobel`,
            
            data: queryCam.map(item => item.nombre_prix_nobel),
           //backgroundColor: queryCam.Gender.map((_, index) => `hsl(${index * 360 / categories.length}, 70%, 50%)`),
            hoverOffset: 4
        }]
    },
    options: {
        plugins: {
            legend: {
                display: true,
                position: 'top',
            },
            title: {
                display: false,
                text: `Distribution des Prix Nobel par Catégorie en`
            }
        },
        responsive: true,
    }
    });
    
    }


document.getElementById('historique').addEventListener('click', function() {
grapheLineaire();
});

//Listener pour la generartion des historiques sur plusieurs graphs 
document.querySelectorAll('.dropdown-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        var typeGraphique = e.target.getAttribute('data-chart-type');

        var categories = [...new Set(donneesHistorique.map(item => item.Nom_catégorie))];
        var intervalles = [...new Set(donneesHistorique.map(item => item.Intervalle))].sort();
        
        var datasets = categories.map(catégorie => {
            var dataPourCategorie = donneesHistorique.filter(item => item.Nom_catégorie === catégorie);
            var data = intervalles.map(intervalle => {
                var item = dataPourCategorie.find(item => item.Intervalle === intervalle);
                return item ? item.nbLauréats : 0;
            });

            return {
                label: catégorie,
                data: data,
                fill: false,
                backgroundColor: couleurAleatoire(), 
                borderColor: couleurAleatoire(), 
                tension: 0.1 
            };
        });

        
        var options = {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: "Nombre de lauréats"
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: "Intervalle d'années"
                    }
                }
            },
            plugins: {
                legend: {
                    display: true 
                },
                title: {
                    display: true,
                    text: 'Nombre de lauréats par catégorie et intervalle d\'années'
                }
            }
        };

       
        genererGraphique(typeGraphique, {labels: intervalles, datasets: datasets, options: options});
    });
});

document.getElementById('organisation').addEventListener('click', function() {
    var labels = organisation.map(o => o.Organisation);
    var donnees = organisation.map(o => o.NombreLauréats);
    var couleurs = organisation.map(() => couleurAleatoire());
    genererGraphiqueBarres(labels, donnees, couleurs, 'Organisation ayant au moins 10 prix nobels', 10);
});

document.getElementById('pays').addEventListener('click', function() {
    var labels = donneesPaysOrganisation.map(i => i.Pays);
    var donnees = donneesPaysOrganisation.map(i => i.NombreLaureats);
    var couleurs = donneesPaysOrganisation.map(() => couleurAleatoire());
    genererGraphiqueBarres(labels, donnees, couleurs, "Pays d'origine des organisations ayant au moins 5 lauréats", 5);
});

// document.getElementById('CircularDiagram').addEventListener('click', function(){
// var selectedYear = document.getElementById('selectYear').value;
// updatePieGraph(selectedYear);
// });


document.getElementById('selectYear').addEventListener('change', function() {
var selectedYear = this.value;
updateBarGraph(selectedYear); 
});





document.getElementById('SEXE').addEventListener('click', function() {
grapheEnCam2()
});


/*document.getElementById('selectYearCam').addEventListener('click', function() {
var selectedYear = document.getElementById('selectYear').value;
grapheEnCambert(selectedYear);
});*/



if (years.length > 0) {
updateBarGraph(years[0]);
}
});







