import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { Auth } from '../../services/auth';

@Component({
  selector: 'app-registro',
  templateUrl: './registro.html',
  styleUrls: ['./registro.css'],
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule]
})
export class Registro {
  registroForm: FormGroup;
  mensaje: string = ''; // 👈 agrega esta línea

  constructor(private fb: FormBuilder, private auth: Auth) {
    this.registroForm = this.fb.group({
      nombre_completo: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(6)]],
      confirmar_password: ['', Validators.required]
    });
  }

  onSubmit() {
    if (this.registroForm.valid) {
      const datos = this.registroForm.value;

      this.auth.registrarUsuario(datos).subscribe({
        next: (response) => {
          console.log('Respuesta del servidor:', response);
          alert(response.message || 'Registro exitoso');
          this.registroForm.reset();
        },
        error: (err) => {
          console.error('Error en el registro:', err);
          alert('Hubo un problema al registrar el usuario.');
        }
      });
    } else {
      alert('Por favor completa todos los campos correctamente.');
    }
  }
}
