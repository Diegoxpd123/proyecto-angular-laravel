import { Component, OnInit } from '@angular/core';
import { Cliente } from '../../models/cliente.model';
import { ClienteService } from '../../services/cliente.service';
import  Toastify from 'toastify-js';

@Component({
  selector: 'app-cliente-list',
  standalone: false,
  templateUrl: './cliente-list.component.html',
  styleUrl: './cliente-list.component.scss'
})
export class ClienteListComponent implements OnInit  {
    clientes: Cliente[] = [];
    total: number = 0;

  constructor(
    private clienteService: ClienteService
  ) { }

  ngOnInit(): void {
     this.loadDataIntoTable();
  }

  deleteCliente(id: number): void {
    this.clienteService.deleteCliente(id).subscribe(response => {
      this.showSuccessToast('Cliente eliminado');
      this.clientes = this.clientes.filter(cliente => cliente.id != id);
      this.calculateTotal();
    });
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
