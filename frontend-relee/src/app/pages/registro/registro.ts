import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { Auth } from '../../services/auth';
import { Router } from '@angular/router'; // 👈 Importa el router

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

constructor(
  private fb: FormBuilder,
  private auth: Auth,
  private router: Router // 👈 aquí lo agregas
) {
  this.registroForm = this.fb.group({
    nombre_completo: ['', Validators.required],
    email: ['', [Validators.required, Validators.email]],
    password: ['', [Validators.required, Validators.minLength(6)]],
    confirmar_password: ['', Validators.required]
  });
}


onSubmit() {
  if (!this.registroForm.valid) {
    alert('Por favor completa todos los campos correctamente.');
    return;
  }

  console.log("=== DEBUG REGISTRO ===");
  console.log("Formulario válido:", this.registroForm.valid);
  console.log("Valores del formulario:", this.registroForm.value);

  const datos = this.registroForm.value;


  this.auth.registrarUsuario(datos).subscribe({

    
    next: (res: any) => {
      console.log('Respuesta del servidor (raw):', res);

      // Normalizar la respuesta: si viene como string, intentar parsear
      let data: any;
      if (typeof res === 'string') {
        try {
          data = JSON.parse(res);
        } catch (e) {
          // respuesta no JSON, convertir en un objeto genérico
          data = { status: 'error', message: res };
        }
      } else {
        data = res;
      }

      console.log('Respuesta del servidor (normalizada):', data);

      // Comprobaciones tolerantes para considerar éxito
      const status = (data && data.status) ?? data?.success ?? data?.ok ?? data?.statusCode;
      const message = (data && data.message) ?? data?.msg ?? data?.error ?? 'Respuesta inesperada del servidor';

      const isSuccess =
        status === 'success' ||
        status === 'ok' ||
        status === 'SUCCESS' ||
        status === true ||
        status === 1 ||
        status === 200; // algunos APIs usan códigos numéricos

      if (isSuccess) {
        alert(message || 'Usuario registrado correctamente');

          // ✅ Guardamos el nombre en localStorage
           localStorage.setItem('usuario_nombre', datos.nombre_completo);
            this.registroForm.reset();
           this.router.navigate(['/dashboard']);

        
        this.registroForm.reset();
        // ✅ Redirigir al dashboard después del registro exitoso
        this.router.navigate(['/dashboard']);


      } else {
        alert(message || 'Hubo un problema al registrar el usuario.');
      }
    },
    error: (err) => {
      console.error('Error en el registro (HTTP):', err);
      // Si el backend devuelve JSON de error en err.error, intentamos mostrarlo:
      const serverMsg = err?.error?.message || err?.message || 'Hubo un problema al registrar el usuario.';
      alert(serverMsg);
    }
  });
}


}
