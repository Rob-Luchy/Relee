import { Component, OnInit } from '@angular/core';
import { BookCarousel } from "../../components/book-carousel/book-carousel";

// Declaramos bootstrap para evitar problemas de tipo
declare var bootstrap: any;

@Component({
  selector: 'app-inicio',
  templateUrl: './inicio.html',
  styleUrls: ['./inicio.css'],
  imports: [BookCarousel]
})
export class Inicio implements OnInit {

  ngOnInit() {
    const myCarousel = document.querySelector('#carouselExampleAutoplaying');
    if (myCarousel) {
      new bootstrap.Carousel(myCarousel); // Inicia el carrusel de Bootstrap
    }
  }
}
