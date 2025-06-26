import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { AuthService } from '../../services/auth.service';
import { Router } from '@angular/router';

@Component({
  standalone: false,
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrl: './login.component.scss'
})
export class LoginComponent {
  loginForm: FormGroup;
  loading = false; // <- loader

  constructor(
    private fb: FormBuilder,
    private authService: AuthService,
    private router: Router
  ) {
    this.loginForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', Validators.required],
    });
  }

  onSubmit() {
    if (this.loginForm.valid) {
      this.loading = true; // ← inicia loader

      this.authService.login(this.loginForm.value).subscribe({
        next: (res) => {
          localStorage.setItem('token', res.access_token);
          localStorage.setItem('tenant_id', res.tenant_id.toString());
          this.router.navigate(['/dashboard']);
        },
        error: () => {
          alert('Credenciales incorrectas');
          this.loading = false; // ← detiene loader en error
        },
        complete: () => {
          this.loading = false; // ← detiene loader al finalizar
        }
      });
    }
  }
}

