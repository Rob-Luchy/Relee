import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { Auth } from '../../services/auth';
import { LibrosService } from '../../services/libros';

interface Categoria {
  id: number;
  nombre: string;
}

@Component({
  selector: 'app-dashboard',
  standalone: true,
  templateUrl: './dashboard.html',
  styleUrls: ['./dashboard.css'],
  imports: [CommonModule, FormsModule],
})
export class Dashboard implements OnInit {
  usuario = { nombre: 'Usuario', fechaRegistro: 'Octubre 2025' };

  categorias: Categoria[] = [];
  libros: any[] = [];
  notificaciones = [
    'Tu libro "1984" fue solicitado para intercambio.',
    'Has recibido un nuevo mensaje de Ana.',
    'Tu perfil fue actualizado correctamente.',
  ];

  mostrarFormulario = false;
  fotosReales: string[] = [];
  nuevaFoto: string = '';

  // 🔥 CORREGIDO: id_categoria ya no inicia en 0
  nuevoLibro = {
    id_usuario: 0,
    titulo: '',
    autor: '',
    descripcion: '',
    imagen_portada: '',
    estado: 'Usado',
    id_categoria: null,
  };

  constructor(
    private auth: Auth,
    private router: Router,
    private librosService: LibrosService
  ) {}

  ngOnInit() {
    const nombreGuardado = localStorage.getItem('usuario_nombre');
    if (nombreGuardado) this.usuario.nombre = nombreGuardado;

    const usuario = JSON.parse(localStorage.getItem('usuario') || '{}');
    this.nuevoLibro.id_usuario = usuario.id || 0;

    this.cargarCategorias();
    this.cargarLibros();
  }

  // 🔥 CORREGIDO: Ahora usa localhost
  cargarCategorias() {
    fetch('http://localhost/backend-relee/categorias.php')
      .then((r) => r.json())
      .then((data) => {
        if (data.status === 'success') {
          this.categorias = data.data;
        } else {
          console.warn(data.message);
        }
      })
      .catch((err) => console.error('Error al cargar categorías:', err));
  }

  cargarLibros() {
    this.librosService.obtenerLibros(this.nuevoLibro.id_usuario).subscribe({
      next: (res) => {
        if (res.status === 'success') this.libros = res.data;
        else console.warn(res.message);
      },
      error: (err) => console.error('Error al cargar libros:', err),
    });
  }

  registrarLibro() {
    if (
      !this.nuevoLibro.titulo ||
      !this.nuevoLibro.imagen_portada ||
      !this.nuevoLibro.id_categoria
    ) {
      alert('Completa título, portada y categoría.');
      return;
    }

    this.librosService.registrarLibro(this.nuevoLibro).subscribe({
      next: (res) => {
        if (res.status === 'success') {
          alert('Libro registrado correctamente');
          this.mostrarFormulario = false;
          this.cargarLibros();

          // Reiniciar formulario
          this.nuevoLibro = {
            id_usuario: this.nuevoLibro.id_usuario,
            titulo: '',
            autor: '',
            descripcion: '',
            imagen_portada: '',
            estado: 'Usado',
            id_categoria: null,
          };
        } else {
          alert(res.message || 'Error al registrar libro.');
        }
      },
      error: (err) => console.error('Error al registrar libro:', err),
    });
  }

  onFileSelected(event: any) {
    const archivos: FileList = event.target.files;
    if (!archivos || archivos.length === 0) return;

    Array.from(archivos).forEach((archivo) => {
      const formData = new FormData();
      formData.append('foto', archivo);

      fetch('http://localhost/backend-relee/subir_foto.php', {
        method: 'POST',
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.status === 'success') {
            this.fotosReales.push(data.ruta);
          } else {
            alert('Error al subir imagen: ' + data.message);
          }
        })
        .catch((err) => console.error('Error al subir imagen:', err));
    });
  }

  cerrarSesion() {
    localStorage.removeItem('usuario_nombre');
    this.auth.cerrarSesion();
    alert('Sesión cerrada correctamente');
    this.router.navigate(['/login']);
  }

  agregarFoto() {
    if (!this.nuevaFoto.trim()) {
      alert('Por favor ingresa una URL válida.');
      return;
    }

    this.fotosReales.push(this.nuevaFoto.trim());
    this.nuevaFoto = '';
  }
}
