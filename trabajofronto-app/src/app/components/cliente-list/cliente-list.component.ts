import { Component, OnInit } from '@angular/core';
import { Cliente } from '../../models/cliente.model';
import { ClienteService } from '../../services/cliente.service';
import Toastify from 'toastify-js';
import { TenantService } from '../../services/tenant.service';
import { HttpClient } from '@angular/common/http';
import { UsuarioService } from '../../services/usuario.service';
import { UsuariotenantService } from '../../services/usuariotenant.service';

@Component({
  selector: 'app-cliente-list',
  standalone: false,
  templateUrl: './cliente-list.component.html',
  styleUrl: './cliente-list.component.scss'
})
export class ClienteListComponent implements OnInit {
  clientes: Cliente[] = [];
  total: number = 0;
  loading = false;
  tenants: any[] = [];
  selectedTenant: string = '';
  userid: number | null = null;

  constructor(
    private clienteService: ClienteService,
    private tenantService: TenantService, private http: HttpClient,
    private usuarioService: UsuarioService,
    private usuariotenantService: UsuariotenantService
  ) { }



  ngOnInit(): void {
    this.userid = this.usuarioService.getUsuarioid();
    this.fetchTenants(this.userid);
    const storedTenant = this.tenantService.getTenant();
    if (storedTenant) {
      this.selectedTenant = storedTenant;
      this.setTenantInBackend(storedTenant);
    }
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
    this.loading = true;
    const tenantId = event.target.value;
    this.selectedTenant = tenantId;
    this.tenantService.setTenant(tenantId);
    this.setTenantInBackend(tenantId);
  }

  setTenantInBackend(tenantId: string): void {
    this.http.post('http://127.0.0.1:8000/api/tenants/select', { tenant_id: tenantId }).subscribe(() => {
      this.loadDataIntoTable();
    });
  }


  deleteCliente(id: number): void {

    this.clienteService.deleteCliente(id).subscribe(response => {
      this.showSuccessToast('Cliente eliminado');
      this.clientes = this.clientes.filter(cliente => cliente.id != id);
      this.calculateTotal();
    });
  }

  private loadDataIntoTables(tenantId: string): void {
    //this.clienteService.getClientes(tenantId).subscribe(clientes => {
    //this.clientes = clientes;
    //this.calculateTotal();
    //});
  }

  private loadDataIntoTable(): void {
    this.clienteService.getClientes().subscribe(clientes => {
      this.clientes = clientes;
      this.calculateTotal();
    });
  }


  private calculateTotal(): void {
    this.total = this.clientes.length;

  }

  private showSuccessToast(message: string): void {
    Toastify({
      text: message,
      close: true,
      gravity: "bottom",
      position: "center",
      stopOnFocus: true,
      style: {
        background: "#189586",
      }
    }).showToast();
  }

}
