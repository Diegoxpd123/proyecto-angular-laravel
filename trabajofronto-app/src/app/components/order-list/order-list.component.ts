import { Component, OnInit } from '@angular/core';
import { Order } from '../../models/order.model';
import { OrderService } from '../../services/order.service';
import  Toastify from 'toastify-js';

@Component({
  selector: 'app-order-list',
  standalone: false,
  templateUrl: './order-list.component.html',
  styleUrl: './order-list.component.scss'
})
export class OrderListComponent {
    orders: Order[] = [];
    total: number = 0;

  constructor(
    private orderService: OrderService
  ) { }

  ngOnInit(): void {
     this.loadDataIntoTable();
  }

  deleteOrder(id: number): void {
    this.orderService.deleteOrder(id).subscribe(response => {
      this.showSuccessToast('Orden eliminada');
      this.orders = this.orders.filter(order => order.id != id);
      this.calculateTotal();
    });
  }

   private loadDataIntoTable(): void {
    this.orderService.getOrders().subscribe(orders => {
      this.orders = orders;
      this.calculateTotal();
    });
  }

  private calculateTotal(): void {
    this.total = this.orders.length;

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
