@foreach($danhSachVanBanDen as $vanBanDen)
    <tr>
        <td class="text-center">
            <input type="checkbox" name="duyet[{{ $vanBanDen->id }}]" value="{{ $vanBanDen->id }}" class="sub-check-ban-ban-den">
        </td>
        <td class="text-center">
            {{ $vanBanDen->so_den }}
        </td>
        <td class="so-ky-hieu">
            {{ $vanBanDen->so_ky_hieu }}
        </td>
        <td>
            {{ $vanBanDen->trich_yeu }}
        </td>
    </tr>
@endforeach
