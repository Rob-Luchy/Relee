import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { Auth } from '../../services/auth';

@Component({
  selector: 'app-header',
  templateUrl: './header.html',
  styleUrls: ['./header.css'],
  standalone: true,
  imports: [CommonModule]
})
export class Header implements OnInit {
  nombreUsuario: string | null = null;

  constructor(private router: Router, private auth: Auth) {}

  ngOnInit() {
    // 👇 Escucha los cambios del nombre en tiempo real
    this.auth.usuarioNombre$.subscribe(nombre => {
      console.log('Cambio detectado en el header:', nombre); // <-- útil para depurar
      this.nombreUsuario = nombre;
    });
  }
irAlDashboard() {
  this.router.navigate(['/dashboard']);
}

  cerrarSesion() {
    this.auth.cerrarSesion(); // 🔥 actualiza el BehaviorSubject
    this.router.navigate(['/login']);
  }
}
