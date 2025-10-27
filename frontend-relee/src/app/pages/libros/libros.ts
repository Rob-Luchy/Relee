import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { LibrosService } from '../../services/libros';

@Component({
  selector: 'app-libros',
  templateUrl: './libros.html',
  styleUrls: ['./libros.css'],
  standalone: true,
  imports: [CommonModule, FormsModule]
})
export class Libros implements OnInit {
  libros: any[] = [];
  nuevoLibro = {
    id_usuario: 0,
    titulo: '',
    autor: '',
    descripcion: '',
    id_categoria: null,
    imagen_portada: '',
    estado: 'Usado',
    disponibilidad: 'Disponible'
  };

  constructor(private librosService: LibrosService) {}

  ngOnInit() {
    const usuario = JSON.parse(localStorage.getItem('usuario') || '{}');
    this.nuevoLibro.id_usuario = usuario.id;

    this.cargarLibros();
  }

  cargarLibros() {
    this.librosService.obtenerLibros(this.nuevoLibro.id_usuario).subscribe({
      next: (res) => {
        if (res.status === 'success') {
          this.libros = res.data;
        } else {
          console.warn(res.message);
        }
      },
      error: (err) => console.error('Error al cargar libros:', err)
    });
  }

  registrarLibro() {
    if (!this.nuevoLibro.titulo || !this.nuevoLibro.imagen_portada) {
      alert('Por favor completa el título y la imagen de portada.');
      return;
    }

    this.librosService.registrarLibro(this.nuevoLibro).subscribe({
      next: (res) => {
        if (res.status === 'success') {
          alert('Libro registrado correctamente.');
          this.cargarLibros();
          this.nuevoLibro.titulo = '';
          this.nuevoLibro.autor = '';
          this.nuevoLibro.descripcion = '';
          this.nuevoLibro.imagen_portada = '';
        } else {
          alert(res.message || 'Error al registrar el libro.');
        }
      },
      error: (err) => console.error('Error al registrar libro:', err)
    });
  }
}
