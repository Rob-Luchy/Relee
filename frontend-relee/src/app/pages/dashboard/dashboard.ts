import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { Auth } from '../../services/auth';


@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.html',
  styleUrls: ['./dashboard.css'],
  standalone: true,
  imports: [CommonModule]
})
export class Dashboard implements OnInit {
  usuario = {
    nombre: 'Usuario',
    fechaRegistro: 'Octubre 2025'
  };

  libros = [
    { titulo: 'El Principito', autor: 'Antoine de Saint-Exupéry', imagen: 'assets/img/book1.jpg' },
    { titulo: '1984', autor: 'George Orwell', imagen: 'assets/img/book2.jpg' },
    { titulo: 'Cien años de soledad', autor: 'Gabriel García Márquez', imagen: 'assets/img/book3.jpg' },
  ];

  notificaciones = [
    'Tu libro "1984" fue solicitado para intercambio.',
    'Has recibido un nuevo mensaje de Ana.',
    'Tu perfil fue actualizado correctamente.'
  ];
constructor(private auth: Auth, private router: Router){}
  ngOnInit() {
    // ✅ Recuperar el nombre del usuario
    const nombreGuardado = localStorage.getItem('usuario_nombre');
    if (nombreGuardado) {
      this.usuario.nombre = nombreGuardado;
    }
  }

  cerrarSesion() {
  // Elimina los datos del usuario guardados
  localStorage.removeItem('usuario_nombre');

  // Mensaje opcional
  alert('Sesión cerrada correctamente');

  // Redirige al login
  this.router.navigate(['/login']);
  this.auth.cerrarSesion();  // ✅ Esto actualiza el BehaviorSubject
}

}
