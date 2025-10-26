import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { Auth } from '../../services/auth';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.html',
  styleUrls: ['./login.css'],
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule]
})
export class Login {
  loginForm: FormGroup;

  constructor(private fb: FormBuilder, private auth: Auth, private router: Router) {
    this.loginForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', Validators.required]
    });
  }

  onSubmit() {
    if (!this.loginForm.valid) {
      alert('Por favor ingresa tus credenciales.');
      return;
    }

    const datos = this.loginForm.value;

    this.auth.loginUsuario(datos).subscribe({
      next: (res: any) => {
        console.log('Respuesta del servidor:', res);

        if (res.status === 'success') {
          alert('Inicio de sesión exitoso');
          localStorage.setItem('usuario', JSON.stringify(res.user)); // Guardar usuario en el navegador
          this.router.navigate(['/dashboard']); // Redirigir al dashboard
        } else {
          alert(res.message || 'Credenciales incorrectas');
        }
      },
      error: (err) => {
        console.error('Error en el login:', err);
        alert('Error al conectar con el servidor.');
      }
    });
  }
}
