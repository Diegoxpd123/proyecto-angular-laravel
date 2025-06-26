import { Component, OnInit } from '@angular/core';
import { BranchService } from '../../services/branch.service';

@Component({
  standalone: false,
  selector: 'app-branches',
  templateUrl: './branches.component.html',
  styleUrls: ['./branches.component.scss']
})
export class BranchesComponent implements OnInit {
  branches: any[] = [];

  constructor(private branchService: BranchService) {}

  ngOnInit(): void {
    this.branchService.getAll().subscribe({
      next: (res) => this.branches = res,
      error: (err) => console.error('Error cargando sucursales', err)
    });
  }
}
