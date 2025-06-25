import { Component, ViewChild, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { BookService } from '../../services/book.service';
import Toastify from 'toastify-js';
import { TenantService } from '../../services/tenant.service'; // Asegúrate de tener este servicio
import { HttpClient } from '@angular/common/http';
import { UsuarioService } from '../../services/usuario.service';
import { UsuariotenantService } from '../../services/usuariotenant.service';

@Component({
  selector: 'app-home',
  standalone: false,
  templateUrl: './home.component.html',
  styleUrl: './home.component.scss'
})

export class HomeComponent implements OnInit {
  email = '';
  password = '';
  showTenantModal = false;
  loading = false;
  tenants: any[] = [];
  selectedTenant: string = '';

  @ViewChild('tenantModal') tenantModal: any;

  constructor(
    private tenantService: TenantService,
    private router: Router, private http: HttpClient,
    private usuarioService: UsuarioService,
    private usuariotenantService: UsuariotenantService
  ) { }


  loginForm: FormGroup = new FormGroup({
    email: new FormControl(''),
    password: new FormControl(''),
  });

  ngOnInit(): void {

  }


  login() {
    if (this.loginForm.invalid) {
      Toastify({
        text: 'Por favor, completa los campos correctamente',
        backgroundColor: 'linear-gradient(to right, #ff5f6d, #ffc371)',
        duration: 3000
      }).showToast();
      return;
    }

    const { email, password } = this.loginForm.value;


    this.usuarioService.getUsuarios().subscribe(usuarios => {
      const usuarioValido = usuarios.find(
        u => u.correo === email && u.contra === password && u.is_active === 1 && u.is_deleted === 0
      );

      if (usuarioValido) {
        this.usuarioService.setUsuario(usuarioValido.id);
        this.fetchTenants(usuarioValido.id); // carga los tenants
        this.showTenantModal = true;
      } else {
        Toastify({

          text: 'Usuario o contraseña incorrectos' + email + ' clave ' + password,
          backgroundColor: 'linear-gradient(to right, #ff5f6d, #ffc371)',
          duration: 3000
        }).showToast();
      }
    });
  }


  closeModal() {
    this.showTenantModal = false;
  }



  fetchTenants(usuarioid: number | null): void {
    if (usuarioid === null) return;

    this.usuariotenantService.getUsuariosTenants().subscribe(usuarioTenants => {
      const tenantIds = usuarioTenants
        .filter(ut => ut.usuarioid === usuarioid.toString() && ut.is_active && !ut.is_deleted)
        .map(ut => Number(ut.tenantid));

      this.tenantService.getTenants().subscribe(allTenants => {
        this.tenants = allTenants.filter(t => tenantIds.includes(t.id));
      });
    });
  }


  onTenantChange(event: any): void {
    this.showTenantModal = false;
    this.loading = true;
    const tenantId = event.target.value;
    this.selectedTenant = tenantId;
    this.tenantService.setTenant(tenantId);
    this.setTenantInBackend(tenantId);
  }

  setTenantInBackend(tenantId: string): void {
    this.http.post('http://127.0.0.1:8000/api/tenants/select', { tenant_id: tenantId }).subscribe(() => {
      this.showTenantModal = false;
      this.loading = false;
      this.router.navigate(['/clientes']);
    });
  }

}
